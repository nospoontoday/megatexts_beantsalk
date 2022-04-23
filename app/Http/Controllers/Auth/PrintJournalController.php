<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePrintJournalRequest;
use App\Http\Requests\ImportPrintJournalRequest;
use App\Http\Requests\UpdatePrintJournalRequest;
use App\Imports\PrintJournalsImport;
use App\Models\PrintJournal;
use App\Models\Editor;
use App\Models\Type;
use App\Models\Product;

use Illuminate\Http\Request;

class PrintJournalController extends Controller
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
        $printJournals = PrintJournal::with(
            [
                'product',
                'editor',
            ]
        )
        ->latest()
        ->paginate(5);

        return view('auth.printJournals.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.printJournals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePrintJournalRequest $request)
    {
        $validated = $request->validated();

        $editor = Editor::firstOrCreate(['name' => $validated['editor']]);

        $type = Type::where('name', 'print-journals')->first();

        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $validated['title'],
            'quantity' => $validated['quantity'],
            'price'    => $validated['price'],
            'subject'  => $validated['subject'],
        ]);

        $printJournal = PrintJournal::create([
            'product_id' => $product->id,
            'editor_id'  => $editor->id,
            'issn'       => $validated['issn'],
            'issue_number' => $validated['issue_number'],
        ]);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $printJournal->photos()->create(
                [
                    'uri' => $image,
                ]
            );
        }

        return back()->with('success', 'Print Journal created successfully');
    }

    public function import(ImportPrintJournalRequest $request) 
    {
        $validated = $request->validated();

        $import = new PrintJournalsImport;

        $import->import($validated['print_journals_import']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        return back()->with('success', 'Print Journals imported successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $printJournal = PrintJournal::with([
            'product',
            'editor',
            'photo',
        ])->find($id);

        $photo = $printJournal->photo ? $printJournal->photo->uri : "";

        return view('auth.printJournals.show', compact('printJournal', 'photo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $printJournal = PrintJournal::with([
            'product',
            'editor',
            'photo',
        ])->find($id);

        $photo = $printJournal->photo ? $printJournal->photo->uri : "";

        return view('auth.printJournals.edit', compact('printJournal', 'photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrintJournalRequest $request, $id)
    {
        $validated = $request->validated();
        $editor = Editor::updateOrCreate(
            ['name' => $validated['editor']['name']]
        );

        $type = Type::where('name', 'print-journals')->first();

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

        $printJournal = PrintJournal::find($id);

        if($request->file('image')) {
            $image = $request->file('image')->hashName();

            $request->image->move(public_path('products'), $image);

            $printJournal->photos()->updateOrCreate(
                [
                    'uri' => $image,
                ]
            );
        }
        
        $printJournal->update([
            'editor_id' => $editor->id,
            'product_id' => $product->id,
            'issn' => $validated['issn'],
            'issue_number' => $validated['issue_number'],
        ]);
            
        return back()->with('success', 'Print Journal updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $printJournal = PrintJournal::find($id);
        $printJournal->product()->delete();
        $printJournal->delete();

        return back()->with('success', 'Print Journal deleted successfully');
    }
}
