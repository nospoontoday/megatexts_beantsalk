<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\CreateEBookRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportEBookRequest;
use App\Http\Requests\UpdateEBookRequest;
use App\Imports\EBooksImport;
use App\Models\EBook;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Product;
use App\Models\Type;
use App\Models\Platform;
use App\Models\AccessModel;

class EBookController extends Controller
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
        return view('auth.eBooks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.eBooks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEBookRequest $request)
    {
        $validated = $request->validated();

        $author = Author::firstOrCreate(['name' => $validated['author']]);
        $publisher = Publisher::firstOrCreate(['name' => $validated['publisher']]);
        $platform = Platform::firstOrCreate(['name' => $validated['platform']]);
        $access_model = AccessModel::firstOrCreate(['name' => $validated['access_model']]);

        //find the type
        $type = Type::where('name', 'e-books')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $eBook = EBook::create([
            'product_id' => $product->id,
            'author_id'  => $author->id,
            'publisher_id' => $publisher->id,
            'e_isbn'      => $validated['e_isbn'],
            'publication_year' => $validated['publication_year'],
            'platform_id'              => $platform->id,
            'access_model_id'          => $access_model->id,
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $eBook->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'E-Book created successfully');
    }

    public function import(ImportEBookRequest $request) 
    {
        $validated = $request->validated();

        $import = new EBooksImport;

        $import->import($validated['e_books_import']);

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
        $eBook = EBook::with([
            'product',
            'author',
            'publisher',
            'platform',
            'accessModel',
            'photo',
        ])->find($id);

        $photo = $eBook->photo ? $eBook->photo->uri : "";

        return view('auth.eBooks.show', compact('eBook', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eBook = EBook::with([
            'product',
            'author',
            'publisher',
            'platform',
            'accessModel',
            'photo',
        ])->find($id);

        $photo = $eBook->photo ? $eBook->photo->uri : "";

        return view('auth.eBooks.edit', compact('eBook', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEBookRequest $request, $id)
    {
        $validated = $request->validated();
        $author = Author::updateOrCreate(
            ['name' => $validated['author']['name']]
        );

        $publisher = Publisher::updateOrCreate(
            ['name' => $validated['publisher']['name']]
        );

        $platform = Platform::updateOrCreate(
            ['name' => $validated['platform']['name']]
        );

        $accessModel = AccessModel::updateOrCreate(
            ['name' => $validated['accessModel']['name']]
        );

        $type = Type::where('name', 'e-books')->first();

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

        $eBook = EBook::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $eBook->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }

        $eBook->update([
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'platform_id' => $platform->id,
            'access_model_id' => $accessModel->id,
            'product_id' => $product->id,
            'e_isbn' => $validated['e_isbn'],
            'publication_year' => $validated['publication_year'],
        ]);
            
        return back()->with('success', 'eBook updated successfully');
    }
}
