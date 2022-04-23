<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportVendorRequest as RequestsImportVendorRequest;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Imports\VendorsImport;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Invoke role permission middleware
     *
     * @return \Illuminate\Http\Response
     */    
    function __construct()
    {
        $this->middleware('permission:vendor-list', ['only' => ['index']]);
        $this->middleware('permission:vendor-edit', ['only' => ['edit','update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('auth.vendors.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.vendors.create');
    }

    public function import(RequestsImportVendorRequest $request) 
    {
        $validated = $request->validated();

        $import = new VendorsImport;

        $import->import($validated['vendors_import']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        return back()->with('success', 'Vendors imported successfully!');
    }    

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = Vendor::find($id);
        return view('auth.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = Vendor::with(['contact', 'presentAddress'])
            ->find($id);

        return view('auth.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVendorRequest  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        $validated = $request->validated();

        $vendor->update($validated);

        $vendor->contacts()->update([
            'mobile' => $vendor['contact']['mobile'],
        ]);

        $vendor->addresses()->update([
            'website' => $validated['presentAddress']['website'],
            'present_address' => $validated['presentAddress']['present_address'],
            'email' => $validated['presentAddress']['email'],
            'city'  => $validated['presentAddress']['city'],
            'state'  => $validated['presentAddress']['state'],
            'zip'  => $validated['presentAddress']['zip'],
        ]);

        return back()->with('success', 'Vendor updated successfully');
    }
}
