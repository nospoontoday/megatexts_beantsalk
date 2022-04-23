<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Purpose;
use App\Models\Quotation;
use Carbon\Carbon;

class QuotationController extends Controller
{
    /**
     * Invoke role permission middleware
     *
     * @return \Illuminate\Http\Response
     */    
    function __construct()
    {
        $this->middleware('permission:quotation-list', ['only' => ['index']]);
        $this->middleware('permission:quotation-edit', ['only' => ['edit','update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.quotations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quotation = Quotation::orderBy('id', 'DESC')->first();

        $pr_number = generate_unique_id("Q", Carbon::now()->year, "-", $quotation->id, $quotation->created_at->format("Y"));
        return view('auth.quotations.create', compact('pr_number'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::with([
            'purpose', 
            'products' => function($query){
                $query->with([
                    'type',
                    'printBook', 
                    'printBook.author', 
                    'printBook.publisher',
                    'printJournal',
                    'printJournal.editor',
                ]);
            },
        ])->where('id', $id)->first();

        return view('auth.quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quotation = Quotation::with('products')->find($id);

        $purposes = Purpose::get();

        return view('auth.quotations.edit', compact('quotation', 'purposes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuotationRequest  $request
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        $validated = $request->validated();

        $quotation->update($validated);

        return back()->with('success', 'Quotation updated successfully');
    }
}
