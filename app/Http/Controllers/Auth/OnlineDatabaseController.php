<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\CreateOnlineDatabaseRequest;
use App\Http\Requests\ImportOnlineDatabaseRequest;
use App\Http\Requests\UpdateOnlineDatabaseRequest;
use App\Imports\OnlineDatabasesImport;
use App\Models\AccessModel;
use App\Models\OnlineDatabase;
use App\Models\Platform;
use App\Models\Publisher;
use App\Models\Type;
use App\Models\Product;

use Illuminate\Http\Request;

class OnlineDatabaseController extends Controller
{
    public function __construct()
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
        return view('auth.onlineDatabases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.onlineDatabases.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOnlineDatabaseRequest $request)
    {
        $validated = $request->validated();

        $publisher = Publisher::firstOrCreate(['name' => $validated['publisher']]);

        $platform = Platform::firstOrCreate(['name' => $validated['platform']]);

        $access_model = AccessModel::firstOrCreate(['name' => $validated['access_model']]);

        $type = Type::where('name', 'online-databases')->first();

        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $onlineDatabase = OnlineDatabase::create([
            'product_id' => $product->id,
            'publisher_id'  => $publisher->id,
            'platform_id' => $platform->id,
            'access_model_id' => $access_model->id,
            'subscription_period' => $validated['subscription_period'],
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $onlineDatabase->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'Online Database created successfully');
    }

    public function import(ImportOnlineDatabaseRequest $request) 
    {
        $validated = $request->validated();

        $import = new OnlineDatabasesImport;

        $import->import($validated['online_databases_import']);

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
        $onlineDatabase = OnlineDatabase::with([
            'product',
            'publisher',
            'platform',
            'accessModel',
            'photo',
        ])->find($id);

        $photo = $onlineDatabase->photo ? $onlineDatabase->photo->uri : "";

        return view('auth.onlineDatabases.show', compact('onlineDatabase', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $onlineDatabase = OnlineDatabase::with([
            'product',
            'publisher',
            'platform',
            'accessModel',
            'photo',
        ])->find($id);

        $photo = $onlineDatabase->photo ? $onlineDatabase->photo->uri : "";

        return view('auth.onlineDatabases.edit', compact('onlineDatabase', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOnlineDatabaseRequest $request, $id)
    {
        $validated = $request->validated();

        $publisher = Publisher::updateOrCreate(
            ['name' => $validated['publisher']['name']]
        );

        $platform = Platform::updateOrCreate(
            ['name' => $validated['platform']['name']]
        );

        $accessModel = AccessModel::updateOrCreate(
            ['name' => $validated['accessModel']['name']]
        );

        $type = Type::where('name', 'online-databases')->first();

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

        $onlineDatabase = OnlineDatabase::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $onlineDatabase->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }

        $onlineDatabase->update([
            'publisher_id' => $publisher->id,
            'platform_id' => $platform->id,
            'access_model_id' => $accessModel->id,
            'product_id' => $product->id,
            'subscription_period' => $validated['subscription_period'],
        ]);
            
        return back()->with('success', 'Online Database updated successfully');
    }
}
