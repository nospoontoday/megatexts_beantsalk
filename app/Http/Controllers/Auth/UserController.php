<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Invoke role permission middleware
     *
     * @return \Illuminate\Http\Response
     */    
    function __construct()
    {
        $this->middleware('permission:user-list', ['only' => ['index']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
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
        $user = User::find(auth()->user()->id);
        return view('auth.users.index', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request User input
     * * @param int                      $id      User's id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'image' => 'required|mimes:jpg,png,jpeg|max:5048',
        ]);

        $image = $request->file('image')->hashName();

        $request->image->move(public_path('profile_pictures'), $image);
        $user = User::find($id);
        $user->profile_pic = $image;
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'User updated successfully');
    }
}
