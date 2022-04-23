<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public $branch_name;

    public $role;

    /**
     * Invoke role permission middleware
     *
     * @return \Illuminate\Http\Response
     */    
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * 
     * @param \Illuminate\Http\Request $request to handle pagination
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = [];

        $this->branch_name = $request->get('branch') ? $request->get('branch') : auth()->user()->branches->first()->name;

        $this->role = $request->get('role');

        $branch_name = ['name' => $this->branch_name];

        $admins = Branch::when($this->branch_name, function($query, $term){
            return $query->where(DB::raw('lower(name)'), '=', strtolower($term));
        })
        ->with([
            'users' => function($query) {
                return $query->where('is_admin', 1);
            },
        ])
        ->whereHas('users')
        ->first()
        ->users;

        $branch = Branch::when($this->branch_name, function($query, $term){
            return $query->where(DB::raw('lower(name)'), '=', strtolower($term));
        })
        ->with([
            'users' => function($query) {
                return $query->with(['roles' => function($r_query){
                    if($this->role == null)
                        return;
                    return $r_query->where('roles.id', '=', $this->role);
                }]);
            },
        ])
        ->first();

        $roles = Role::all();

        $branches = Branch::all();

        if(! is_null($branch))
            $users = $this->flattenBranchRoles($branch->users);

        return view('admin.users.index', compact('users', 'roles', 'branch', 'branches', 'branch_name', 'admins'));
    }

    private function flattenBranchRoles($branch_users) {
        $users = [];

        foreach($branch_users as $user) {
            foreach($user->getRoleNames() as $role_name) {
                $users[$role_name][] = $user;
            }
        }

        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CreateUserRequest $request to handle incoming data
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        
        $input = $request->validated();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($input['roles']);

        if($input['roles'] == config('constants.roles.ADMIN')) {
            $user->is_admin = 1;
            $user->save();
        }

        $user->branches()->attach($input['branches']);

        return back()->with('success', 'User successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id User's id
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id User's id
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Handle incoming data
     * @param int                      $id      User's id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();

        if (! empty($validated['password']) ) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            $validated = Arr::except($validated, array('password'));
        }

        $user = User::find($id);
        $user->update($validated);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id User's id
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->is_admin) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Unauthorized access');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}
