<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\LibraryFixture;
use App\Models\Manufacturer;
use App\Models\Type;
use App\Models\Product;

use Illuminate\Http\Request;
use App\Http\Requests\CreateLibraryFixtureRequest;
use App\Http\Requests\ImportLibraryFixtureRequest;
use App\Http\Requests\UpdateLibraryFixtureRequest;
use App\Imports\LibraryFixturesImport;

class LibraryFixtureController extends Controller
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
        return view('auth.libraryFixtures.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.libraryFixtures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLibraryFixtureRequest $request)
    {
        $validated = $request->validated();

        $manufacturer = Manufacturer::firstOrCreate(['name' => $validated['manufacturer']]);

        //find the type
        $type = Type::where('name', 'library-fixtures')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $libraryFixture = LibraryFixture::create([
            'product_id'    => $product->id,
            'manufacturer_id'  => $manufacturer->id,
            'item_code'     => $validated['item_code'],
            'dimension'     => $validated['dimension'],
            'vatable_sales' => $validated['vatable_sales'],
            'vat'           => $validated['vat'],
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $libraryFixture->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'Library Fixtures created successfully');
    }

    public function import(ImportLibraryFixtureRequest $request) 
    {
        $validated = $request->validated();

        $import = new LibraryFixturesImport;

        $import->import($validated['library_fixtures_import']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        return back()->with('success', 'Library Fixtures imported successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $libraryFixture = LibraryFixture::with([
            'product',
            'manufacturer',
            'photo',
        ])->find($id);

        $photo = $libraryFixture->photo ? $libraryFixture->photo->uri : "";

        return view('auth.libraryFixtures.show', compact('libraryFixture', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $libraryFixture = LibraryFixture::with([
            'product',
            'manufacturer',
            'photo',
        ])->find($id);

        $photo = $libraryFixture->photo ? $libraryFixture->photo->uri : "";

        return view('auth.libraryFixtures.edit', compact('libraryFixture', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLibraryFixtureRequest $request, $id)
    {
        $validated = $request->validated();
        $manufacturer = Manufacturer::updateOrCreate(
            ['name' => $validated['manufacturer']['name']]
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

        $libraryFixture = LibraryFixture::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $libraryFixture->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }

        $libraryFixture->update([
            'manufacturer_id' => $manufacturer->id,
            'product_id' => $product->id,
            'item_code' => $validated['item_code'],
            'dimension' => $validated['dimension'],
            'vatable_sales' => $validated['vatable_sales'],
            'vat' => $validated['vat'],
        ]);
            
        return back()->with('success', 'Library Fixture updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $libraryFixture = LibraryFixture::find($id);
        $libraryFixture->product()->delete();
        $libraryFixture->delete();

        return back()->with('success', 'Library Fixture deleted successfully');
    }
}
