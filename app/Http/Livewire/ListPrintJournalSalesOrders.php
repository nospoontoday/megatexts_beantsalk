<?php

namespace App\Http\Livewire;

use App\Models\Editor;
use App\Models\PrintJournal;
use App\Models\Product;
use App\Models\Type;
use App\Models\User;

class ListPrintJournalSalesOrders extends AbstractListProductsSalesOrders
{
    public $customer;
    public $branches;
    public $salesReps;
    public $productSalesOrders = [];
    public $books;
    public $subTotal;
    public $totalDiscount;
    public $total;

    public $customers;
    public $branch;
    public $salesRep;
    public $iorf;
    public $date;
    public $poNumber;

    public function rules()
    {
        return [
            'customer' => 'required',
            'branch' => 'required',
            'address' => 'required',
            'date' => 'required',
            'iorf' => 'required',
            'city' => 'required',
            'state' => 'required',
            'poNumber' => 'required',
            'email' => 'required',
            'salesRep' => 'required',
            'contact' => 'required',
            'buyer' => 'required',
            'productSalesOrders.*.issn' => 'required',
            'productSalesOrders.*.editor' => 'required',
            'productSalesOrders.*.title' => 'required',
            'productSalesOrders.*.issue_number' => 'required',
            'productSalesOrders.*.quantity' => 'required',
            'productSalesOrders.*.price' => 'required',
        ];
    }

    public function confirmIfAccurateSummary()
    {
        $subTotal = 0;
        $total = 0;
        foreach($this->productSalesOrders as $productSalesOrder) {
            $subTotal += $productSalesOrder['total'];

        }

        $total = $subTotal;

        if($this->subTotal != $subTotal) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'error' => ['Sub total is not accurate'],
            ]);
            throw $error;               
        }

        if($this->total != $total) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'error' => ['Total is not accurate'],
            ]);
            throw $error;                  
        }
    }

    public function mount($branches)
    {
        $this->customer = '';
        $this->branches = $branches;
        $this->salesReps = [];

        $this->productSalesOrders = [
            [
                'issn' => '',
                'editor' => '',
                'title' => '',
                'issue_number' => '',
                'quantity' => 1,
                'price' => 0,
                'total' => 0,
            ]
        ];

        $this->setPrintJournals();
        $this->subTotal = 0;
        $this->totalDiscount = 0;
        $this->total = 0;

        $this->customers = [];
        $this->branch = auth()->user()->branches->first()->id;
        $this->salesReps = User::role('Sales Representative')
            ->with('branches')
            ->whereHas('branches', function($query){
                $query->where('branches.id', $this->branch);
            })
            ->get();

        $this->salesRep = auth()->user()->id;
    }

    public function submit()
    {
        $this->validate();

        $this->productsHaveDuplicates();

        $this->salesOrderHasAtLeastOneProduct();

        $this->confirmIfAccurateSummary();

        $salesOrder = $this->createSalesOrder();

        if(!empty($this->productSalesOrders)) {
            $type = Type::where('name', 'print-books')->first();
            foreach ($this->productSalesOrders as $key => $productSalesOrder) {
                $product = Product::firstOrCreate(
                    ['title' => $productSalesOrder['title']], ['type_id' => $type->id],
                );

                $editor = Editor::firstOrCreate(
                    ['name' => $productSalesOrder['editor']]
                );

                $printJournal = PrintJournal::firstOrCreate(
                    ['product_id' => $product->id], 
                    [
                        'issn' => $productSalesOrder['issn'], 
                        'editor_id' => $editor->id,
                        'issue_number' => $productSalesOrder['issue_number'],
                    ],
                );

                $salesOrder->products()->attach($product->id,
                    [
                        'quantity' => $productSalesOrder['quantity'],
                        'price' => $productSalesOrder['price'],
                    ]
                );
            }

            $this->calculate();

            $salesOrder->total_amount = $this->total;
            $salesOrder->save();
        }

        return redirect()->route('sales-order.index')
        ->with('success', 'Sales Order created successfully');        
    } 

    public function setPrintJournals()
    {
        $this->books = PrintJournal::with([
            'product',
        ])
        ->orderBy('created_at', 'DESC')
        ->get();   
    }

    public function setProduct($index, $issn)
    {
        $printJournal = PrintJournal::with([
                'editor',
                'product',
            ])
            ->where('issn', $issn)->first();

        if($printJournal) {
            $this->productSalesOrders[$index]['editor'] = $printJournal->editor->name ?? null;
            $this->productSalesOrders[$index]['title'] = $printJournal->product->title ?? null;
            $this->productSalesOrders[$index]['issue_number'] = $printJournal->issue_number;
            $this->productSalesOrders[$index]['price'] = $printJournal->product->price ?? 0;
            $this->productSalesOrders[$index]['quantity'] = $printJournal->product->quantity ?? 0;
            $this->productSalesOrders[$index]['total'] = $this->getPerUnitTotal($printJournal);
            $this->calculate();
        }
    }

    public function getPerUnitTotal($printJournal)
    {
        return ($printJournal->product->price * $printJournal->product->quantity);        
    }

    public function calculate()
    {
        $this->subTotal = $this->getSubTotal();
        $this->total = $this->getOverallTotal();
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->productSalesOrders as $productSalesOrder) {
            $subTotal += $productSalesOrder['total'];
        }

        return $subTotal;
    }

    public function getOverallTotal()
    {   
        return $this->subTotal;
    }

    public function addProduct()
    {
        $this->productSalesOrders[] = [
            'issn' => '',
            'editor' => '',
            'title' => '',
            'issue_number' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    public function setQuantity($index, $quantity)
    {
        $this->productSalesOrders[$index]['total'] = $this->getPrintJournalTotal($index, $quantity, 'quantity');
        $this->calculate();
    }

    public function setPrice($index, $price)
    {
        $this->productSalesOrders[$index]['total'] = $this->getPrintJournalTotal($index, $price, 'price');
        $this->calculate();
    }

    public function getPrintJournalTotal($index, $value, $type)
    {
        $quantity = $type == 'quantity'
            ? $value
            : $this->productSalesOrders[$index]['quantity'];

        $price = $type == 'price'
            ? $value
            : $this->productSalesOrders[$index]['price'];

        return ($price * $quantity);
    }

    public function render()
    {
        return view('livewire.list-print-journal-sales-orders');
    }
}
