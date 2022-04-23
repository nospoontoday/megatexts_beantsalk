<?php

namespace App\Http\Controllers\Auth;

use App\Models\PrintBook;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Product;
use App\Models\Type;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePrintBookRequest;
use App\Http\Requests\ImportPrintBookRequest;
use App\Http\Requests\UpdatePrintBookRequest;
use App\Imports\PrintBooksImport;

class PrintBookController extends Controller
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
        return view('auth.printBooks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.printBooks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePrintBookRequest $request)
    {
        $validated = $request->validated();

        $author = Author::firstOrCreate(['name' => $validated['author']]);
        $publisher = Publisher::firstOrCreate(['name' => $validated['publisher']]);

        //find the type
        $type = Type::where('name', 'print-books')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $printBook = PrintBook::create([
            'product_id' => $product->id,
            'author_id'  => $author->id,
            'publisher_id' => $publisher->id,
            'isbn_13'      => $validated['isbn_13'],
            'publication_year' => $validated['publication_year'],
            'discount'         => array_key_exists('discount', $validated) ?? $validated['discount'],
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $printBook->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'Print Book created successfully');
    }

    public function import(ImportPrintBookRequest $request) 
    {
        $validated = $request->validated();

        $import = new PrintBooksImport;

        $import->import($validated['print_books_import']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        return back()->with('success', 'Print Books imported successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $printBook = PrintBook::with([
            'product',
            'author',
            'publisher',
            'photo',
        ])->find($id);

        $photo = $printBook->photo ? $printBook->photo->uri : "";

        return view('auth.printBooks.show', compact('printBook', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $printBook = PrintBook::with([
            'product',
            'author',
            'publisher',
            'photo',
        ])->find($id);

        $photo = $printBook->photo ? $printBook->photo->uri : "";

        return view('auth.printBooks.edit', compact('printBook', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrintBookRequest $request, $id)
    {
        $validated = $request->validated();
        $author = Author::updateOrCreate(
            ['name' => $validated['author']['name']]
        );

        $publisher = Publisher::updateOrCreate(
            ['name' => $validated['publisher']['name']]
        );

        $type = Type::where('name', 'print-books')->first();

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

        $printBook = PrintBook::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $printBook->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }

        $printBook->update([
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'product_id' => $product->id,
            'isbn_13' => $validated['isbn_13'],
            'discount' => $validated['discount'],
            'publication_year' => $validated['publication_year'],
        ]);
            
        return back()->with('success', 'Print Book updated successfully');
    }
}