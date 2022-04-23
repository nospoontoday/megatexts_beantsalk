<?php

namespace App\Http\Livewire;

use App\Models\PrintBook;
use App\Models\PrintJournal;
use App\Models\Product;
use App\Models\Purpose;
use App\Models\Quotation;
use App\Models\Type;
use Livewire\Component;

class EditProductQuotations extends Component
{
    public $quotationId;
    public $quotation;
    public $project_title;
    public $pr_number;
    public $deadline;
    public $bidding_date;
    public $terms_conditions;
    public $purpose_id;
    public $products;
    public $productQuotations = [];
    public $books = [];

    public function rules()
    {
        return [
            'project_title' => 'required',
            'pr_number' => 'required',
            'deadline' => 'required|after:today',
            'bidding_date' => 'required',
            'purpose_id' => 'required',
            'productQuotations.*.title' => 'sometimes|required',
            'productQuotations.*.quantity' => 'required|numeric|min:1',
            'productQuotations.*.type' => 'required',
        ];
    }

    public function productsHaveDuplicates() {
        foreach($this->productQuotations as $key => $productQuotation) {
            foreach($this->productQuotations as $search_key => $search_array){
                if($search_array['title'] == $productQuotation['title'] && $search_array['type'] == $productQuotation['type']) {
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

    public function mount($quotation)
    {
        $this->project_title = $quotation->project_title;
        $this->pr_number = $quotation->pr_number;
        $this->deadline = $quotation->deadline;
        $this->bidding_date = $quotation->bidding_date;
        $this->terms_conditions = $quotation->terms_conditions;
        $this->purpose_id = $quotation->purpose_id;
        $this->products = $quotation->products;
        $this->quotationId = $quotation->id;

        foreach ($this->products as $key => $product) {

            $this->productQuotations[$key] = [
                'product_id' => $product->id,
                'title' => $product->title, 
                'price' => $product->pivot->price, 
                'quantity' => $product->pivot->quantity, 
                'type' => $product->type->name,
                'discount' => $product->pivot->discount,
            ];

            $this->setBooks($product->type->name, $key);
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

    public function update($id)
    {
        $this->validate();

        $this->productsHaveDuplicates();

        $this->validateTypes();

        $quotation = $this->updateQuotation($id);

        if(!empty($this->productQuotations)) {
            
            foreach($this->productQuotations as $key=> $productQuotation) {
                $type = Type::where('name', $productQuotation['type'])->first();

                if($productQuotation['product_id'] != null) {
                    $product = Product::updateOrCreate(
                        [
                            'id' => $productQuotation['product_id'],
                        ], 
                        [
                            'type_id' => $type->id, 
                            'title' => $productQuotation['title']
                        ],
                    );

                    ($product->wasRecentlyCreated)
                        ? $this->attachProducts($quotation, $productQuotation, $product)
                        : $this->quotation->products()->updateExistingPivot($productQuotation['product_id'],                     [
                            'price'     => $productQuotation['price'],
                            'quantity'  => $productQuotation['quantity'],
                        ]);

                } else {
                    $product = Product::firstOrCreate(
                        ['title' => $productQuotation['title'], 'type_id' => $type->id, 'is_quotation' => true],
                    );
    
                    $this->attachProducts($quotation, $productQuotation, $product);                      
                }               
            }
        }

        return redirect()->route('quotations.index')
        ->with('success', 'Quotation Updated successfully');
    }

    public function attachProducts($quotation, $productQuotation, $product)
    {
        $quotation->products()->attach($product->id,
            [
                'price'     => $productQuotation['price'],
                'quantity'  => $productQuotation['quantity'],
            ]
        );  
    }

    public function updateQuotation($id)
    {
        $quotation = Quotation::find($id);
        $quotation->project_title = $this->project_title;
        $quotation->pr_number = $this->pr_number;
        $quotation->deadline = $this->deadline;
        $quotation->bidding_date = $this->bidding_date;
        $quotation->terms_conditions = $this->terms_conditions ?? "";
        $quotation->purpose_id = $this->purpose_id;
        $quotation->save();

        $this->quotation = $quotation;

        return $quotation;
    }


    public function render()
    {
        $purposes = Purpose::get();
        return view('livewire.edit-product-quotations', compact('purposes'));
    }

    public function removeProduct($index)
    {
        if($this->productQuotations[$index]['product_id'] != null) {
            $product = Product::find($this->productQuotations[$index]['product_id']);
            $product->quotations()->detach();

            if($product->is_quotation) {
                $product->delete();

                if($this->productQuotations[$index]['type'] == "print-books") {
                    $product->printBook->delete();
                }

                else if($this->productQuotations[$index]['type'] == "print-journals") {
                    $product->printJournal->delete();
                }
            }
        }

        unset($this->productQuotations[$index]);
        $this->productQuotations = array_values($this->productQuotations);
    }

    public function updateProductList($index)
    {
        $this->productQuotations[$index]['title'] = '';
        $this->setBooks($this->productQuotations[$index]['type'], $index);
    }

    public function addProduct()
    {
        $this->productQuotations[] = ['title' => '', 'price' => 0, 'quantity' => 0, 'discount' => 0, 'type' => 'print-books', 'product_id' => null];
    }

    public function setBooks($type, $index)
    {
        if($type == "print-books") {
            $this->books[$index] = PrintBook::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        } 
        else if ($type == "print-journals") {
            $this->books[$index] = PrintJournal::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        }
     
    }
}
