<?php

namespace App\Http\Livewire;

use App\Models\PrintBook;
use App\Models\PrintJournal;
use App\Models\Product;
use App\Models\Purpose;
use App\Models\Quotation;
use App\Models\Type;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ListProductQuotations extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $productQuotations = [];
    public $quotations = [];
    public $books;
    public $type;
    public $pr_number;

    public function updatedType($type)
    {
        $this->type = $type;
    }

    public function rules()
    {
        return [
            'quotations.project_title' => 'required',
            'pr_number' => 'required',
            'quotations.deadline' => 'required|after:today',
            'quotations.bidding_date' => 'required',
            'quotations.purpose_id' => 'required',
            'quotations.terms_conditions' => 'sometimes|required',
            'productQuotations.*.title' => 'sometimes|required',
            'productQuotations.*.quantity' => 'required|numeric|min:1',
            'productQuotations.*.type' => 'required',
            'productQuotations.*.discount' => 'sometimes|required',
        ];
    }

    public function productsHaveDuplicates() {
        foreach($this->productQuotations as $key => $productQuotation) {
            foreach($this->productQuotations as $search_key => $search_array){
                if($search_array['title'] == $productQuotation['title']) {
                    if($search_key != $key) {
                        $error = \Illuminate\Validation\ValidationException::withMessages([
                            'productQuotations['.$key.'][title]' => ['Duplicates found in products'],
                        ]);
                        throw $error;                        
                    }
                }
            }
        }
    }

    public function validateTypes()
    {
        $validTypes = [];
        $types = Type::get();

        foreach($types as $type) {
            $validTypes[] = $type->name;
        }

        foreach($this->productQuotations as $key => $productQuotation) {
            if (! in_array($productQuotation['type'], $validTypes)) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'productQuotations['.$key.'][type]' => ['Invalid product type found'],
                ]);
                throw $error;     
            }
        }
    }

    public function validatePrNumber()
    {
        $quotation = Quotation::orderBy('id', 'DESC')->first();
        $pr_number = generate_unique_id("Q", Carbon::now('Y'), "-", $quotation->id, $quotation->created_at->format('Y'));
        if($this->pr_number != $pr_number) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'pr_number' => ['Invalid PR Number'],
            ]);
            throw $error;
        }
    }

    public function submit()
    {
        $this->validate();

        $this->productsHaveDuplicates();

        $this->validateTypes();

        $this->validatePrNumber();

        $quotation = $this->createQuotation();

        if(!empty($this->productQuotations)) {
            
            foreach($this->productQuotations as $productQuotation) {
                $type = Type::where('name', $productQuotation['type'])->first();
                $product = Product::firstOrCreate(
                    ['title' => $productQuotation['title']], ['type_id' => $type->id, 'is_quotation' => true],
                );

                if($type->name == 'print-books') {
                    $printBook = PrintBook::firstOrCreate(
                        ['product_id' => $product->id],
                    );
                }

                if($type->name == 'print-journals') {
                    $printJournal = PrintJournal::firstOrCreate(
                        ['product_id' => $product->id],
                    );
                }

                $quotation->products()->attach($product->id,
                    [
                        'price'     => $productQuotation['price'],
                        'quantity'  => $productQuotation['quantity'],
                        'discount'  => $productQuotation['discount'] ?? 0,
                    ]
                );                
            }
        }

        return redirect()->route('quotations.index')
        ->with('success', 'Quotation created successfully');
    }

    public function createQuotation()
    {
        $quotation = Quotation::create([
            'project_title'     => $this->quotations['project_title'],
            'pr_number'         => $this->pr_number,
            'deadline'          => $this->quotations['deadline'],
            'bidding_date'      => $this->quotations['bidding_date'],
            'terms_conditions'  => $this->quotations['terms_conditions'] ?? "",
            'purpose_id'        => $this->quotations['purpose_id'],
        ]);
        
        return $quotation;
    }

    public function setBooks($type)
    {
        if($type == "print-books") {
            $this->books = PrintBook::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        } 
        else if ($type == "print-journals") {
            $this->books = PrintJournal::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        }
     
    }

    public function mount($pr_number)
    {   
        $this->setBooks('print-books');
        $this->pr_number = $pr_number;
    
        $this->productQuotations = [
            ['title' => '', 'price' => 0, 'quantity' => 0, 'discount' => 0, 'type' => 'print-books']
        ];
    }

    public function addProduct()
    {
        $this->productQuotations[] = ['title' => '', 'price' => 0, 'discount' => 0, 'quantity' => 0, 'type' => 'print-books'];
    }

    public function updateProductList($index)
    {
        $this->productQuotations[$index]['title'] = '';
        $this->setBooks($this->productQuotations[$index]['type']);
    }

    public function removeProduct($index)
    {
        unset($this->productQuotations[$index]);
        $this->productQuotations = array_values($this->productQuotations);
    }

    public function render()
    {
        $purposes = Purpose::get();

        return view('livewire.list-product-quotations', compact('purposes'));
    }
}
