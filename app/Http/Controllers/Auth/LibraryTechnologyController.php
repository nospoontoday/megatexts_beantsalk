<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\CreateLibraryTechnologyRequest;
use App\Http\Requests\ImportLibraryTechnologyRequest;
use App\Http\Requests\UpdateLibraryTechnologyRequest;
use App\Imports\LibraryTechnologiesImport;
use App\Models\LibraryTechnology;
use App\Models\Developer;
use App\Models\Type;
use App\Models\Product;

class LibraryTechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {           
        return view('auth.libraryTechnologies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.libraryTechnologies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLibraryTechnologyRequest $request)
    {
        $validated = $request->validated();

        $developer = Developer::firstOrCreate(['name' => $validated['developer']]);

        //find the type
        $type = Type::where('name', 'library-technologies')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $libraryTechnology = LibraryTechnology::create([
            'product_id'            => $product->id,
            'developer_id'          => $developer->id,
            'item_code'             => $validated['item_code'],
            'subscription_period'   => $validated['subscription_period'],
            'vatable_sales'         => $validated['vatable_sales'],
            'vat'                   => $validated['vat'],
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $libraryTechnology->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'Library Technologies created successfully');
    }

    public function import(ImportLibraryTechnologyRequest $request) 
    {
        $validated = $request->validated();

        $import = new LibraryTechnologiesImport;

        $import->import($validated['library_technologies_import']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        return back()->with('success', 'EBooks imported successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $libraryTechnology = LibraryTechnology::with([
            'product',
            'developer',
            'photo',
        ])->find($id);

        $photo = $libraryTechnology->photo ? $libraryTechnology->photo->uri : "";

        return view('auth.libraryTechnologies.show', compact('libraryTechnology', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $libraryTechnology = LibraryTechnology::with([
            'product',
            'developer',
            'photo',
        ])->find($id);

        $photo = $libraryTechnology->photo ? $libraryTechnology->photo->uri : "";

        return view('auth.libraryTechnologies.edit', compact('libraryTechnology', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLibraryTechnologyRequest $request, $id)
    {
        $validated = $request->validated();
        $developer = Developer::updateOrCreate(
            ['name' => $validated['developer']['name']]
        );

        $type = Type::where('name', 'library-fixtures')->first();

        $product = Product::updateOrCreate(
            [
                'type_id' => $type->id,
                'title' => $validated['product']['title']
            ],
            [
                'quantity' => $validated['product']['quantity'],
                'price' => $validated['product']['price'],
                'subject' => $validated['product']['subject'],
            ]
        );

        $libraryTechnology = LibraryTechnology::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $libraryTechnology->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }

        $libraryTechnology->update([
            'developer_id' => $developer->id,
            'product_id' => $product->id,
            'item_code' => $validated['item_code'],
            'subscription_period' => $validated['subscription_period'],
            'vatable_sales' => $validated['vatable_sales'],
            'vat' => $validated['vat'],
        ]);
            
        return back()->with('success', 'Library Technology updated successfully');
    }
}
