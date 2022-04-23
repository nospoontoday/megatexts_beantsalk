<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\ImportCustomerRequest;
use Illuminate\Http\Request;
use App\Imports\CustomersImport;

use App\Models\Customer;

class CustomerController extends Controller
{

    /**
     * Invoke role permission middleware
     *
     * @return \Illuminate\Http\Response
     */    
    function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['index']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('auth.customers.index');
    }

    public function indexToJson(Request $request)
    {
        if($request->ajax()) {
            $customers = Customer::get();
            $customers_mapped = [];
    
            foreach($customers as $customer) {
                $customers_mapped[] = [
                    'label' => $customer->company_name,
                    'value' => $customer->id,
                ];
            }
    
            return response()->json($customers_mapped);            
        }
        
        return [];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        $input = $request->validated();

        $customer = Customer::create($input);

        $customer->addresses()->create($input);
        $customer->contacts()->create($input);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully');
    }

    public function import(ImportCustomerRequest $request) 
    {
        $validated = $request->validated();

        $import = new CustomersImport;

        $import->import($validated['customers_import']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        return back()->with('success', 'Customers imported successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        return view('auth.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);

        return view('auth.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'company_name' => 'required|unique:customers,company_name,' .$id,
            'buyer_name' => 'required',
            'contact.*' => 'required',
            'presentAddress.*' => 'required',
        ]);

        $input = $request->except(['_method', '_token']);

        $customer = Customer::find($id);
        $customer->update($input);

        $customer->contacts()->update([
            'mobile' => $input['contact']['mobile'],
        ]);

        $customer->addresses()->update([
            'website' => $input['presentAddress']['website'],
            'present_address' => $input['presentAddress']['present_address'],
            'email' => $input['presentAddress']['email'],
            'city'  => $input['presentAddress']['city'],
            'state'  => $input['presentAddress']['state'],
            'zip'  => $input['presentAddress']['zip'],
        ]);

        return back()->with('success', 'Customer updated successfully');
    }
}
