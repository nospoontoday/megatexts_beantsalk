<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\AVMaterial;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Product;
use App\Models\Type;

use App\Http\Requests\CreateAVMaterialRequest;
use App\Http\Requests\ImportAVMaterialRequest;
use App\Http\Requests\UpdateAVMaterialRequest;
use App\Imports\AVMaterialsImport;

class AVMaterialController extends Controller
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
        return view('auth.avMaterials.index');  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.avMaterials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAVMaterialRequest $request)
    {
        $validated = $request->validated();

        $author = Author::firstOrCreate(['name' => $validated['author']]);
        $publisher = Publisher::firstOrCreate(['name' => $validated['publisher']]);

        //find the type
        $type = Type::where('name', 'AV-materials')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $avMaterial = AVMaterial::create([
            'product_id' => $product->id,
            'author_id'  => $author->id,
            'publisher_id' => $publisher->id,
            'item_code'      => $validated['item_code'],
            'publication_year' => $validated['publication_year'],
            'discount'         => array_key_exists('discount', $validated) ?? $validated['discount'],
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $avMaterial->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'AV Material created successfully');
    }

    public function import(ImportAVMaterialRequest $request) 
    {
        $validated = $request->validated();

        $import = new AVMaterialsImport;

        $import->import($validated['av_materials_import']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        return back()->with('success', 'AV Materials imported successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $avMaterial = AVMaterial::with([
            'product',
            'author',
            'publisher',
            'photo',
        ])->find($id);

        $photo = $avMaterial->photo ? $avMaterial->photo->uri : "";

        return view('auth.avMaterials.show', compact('avMaterial', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $avMaterial = AVMaterial::with([
            'product',
            'author',
            'publisher',
            'photo',
        ])->find($id);

        $photo = $avMaterial->photo ? $avMaterial->photo->uri : "";

        return view('auth.avMaterials.edit', compact('avMaterial', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAVMaterialRequest $request, $id)
    {
        $validated = $request->validated();
        $author = Author::updateOrCreate(
            ['name' => $validated['author']['name']]
        );

        $publisher = Publisher::updateOrCreate(
            ['name' => $validated['publisher']['name']]
        );

        $type = Type::where('name', 'AV-materials')->first();

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

        $avMaterial = AVMaterial::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $avMaterial->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }

        $avMaterial->update([
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'product_id' => $product->id,
            'item_code' => $validated['item_code'],
            'discount' => $validated['discount'],
            'publication_year' => $validated['publication_year'],
        ]);
            
        return back()->with('success', 'AV Material updated successfully');
    }
}
