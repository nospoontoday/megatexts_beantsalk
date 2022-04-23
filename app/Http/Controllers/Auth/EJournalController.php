<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEJournalRequest;
use App\Http\Requests\ImportEJournalRequest;
use App\Http\Requests\UpdateEJournalRequest;
use App\Imports\EJournalsImport;
use App\Models\AccessModel;
use App\Models\EJournal;
use App\Models\Editor;
use App\Models\Platform;
use App\Models\Publisher;
use App\Models\Product;
use App\Models\Type;

use Illuminate\Http\Request;

class EJournalController extends Controller
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
        return view('auth.eJournals.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.eJournals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEJournalRequest $request)
    {
        $validated = $request->validated();

        $editor = Editor::firstOrCreate(['name' => $validated['editor']]);
        $publisher = Publisher::firstOrCreate(['name' => $validated['publisher']]);
        $platform = Platform::firstOrCreate(['name' => $validated['platform']]);
        $access_model = AccessModel::firstOrCreate(['name' => $validated['access_model']]);

        //find the type
        $type = Type::where('name', 'e-journals')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $eJournal = EJournal::create([
            'product_id'            => $product->id,
            'editor_id'             => $editor->id,
            'publisher_id'          => $publisher->id,
            'e_issn'                => $validated['e_issn'],
            'frequency'             => $validated['frequency'],
            'platform_id'              => $platform->id,
            'access_model_id'          => $access_model->id,
            'subscription_period'   => $validated['subscription_period'],
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $eJournal->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'E-Journal created successfully');
    }

    public function import(ImportEJournalRequest $request) 
    {
        $validated = $request->validated();

        $import = new EJournalsImport;

        $import->import($validated['e_journals_import']);

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
        $eJournal = EJournal::with([
            'product',
            'editor',
            'publisher',
            'platform',
            'accessModel',
            'photo',
        ])->find($id);

        $photo = $eJournal->photo ? $eJournal->photo->uri : "";

        return view('auth.eJournals.show', compact('eJournal', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $eJournal = EJournal::with([
            'product',
            'editor',
            'publisher',
            'platform',
            'accessModel',
            'photo',
        ])->find($id);

        $photo = $eJournal->photo ? $eJournal->photo->uri : "";

        return view('auth.eJournals.edit', compact('eJournal', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEJournalRequest $request, $id)
    {
        $validated = $request->validated();
        $editor = Editor::updateOrCreate(
            ['name' => $validated['editor']['name']]
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

        $eJournal = EJournal::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $eJournal->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }

        $eJournal->update([
            'editor_id' => $editor->id,
            'publisher_id' => $publisher->id,
            'platform_id' => $platform->id,
            'access_model_id' => $accessModel->id,
            'product_id' => $product->id,
            'e_issn' => $validated['e_issn'],
            'frequency' => $validated['frequency'],
            'subscription_period' => $validated['subscription_period'],
        ]);
            
        return back()->with('success', 'eJournal updated successfully');
    }
}
