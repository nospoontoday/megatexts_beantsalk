<?php

namespace App\Http\Livewire;

use App\Models\AccessModel;
use App\Models\Editor;
use App\Models\EJournal;
use App\Models\Platform;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Type;
use App\Models\User;

class ListEJournalSalesOrders extends AbstractListProductsSalesOrders
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
            'productSalesOrders.*.e_issn' => 'required',
            'productSalesOrders.*.editor' => 'required',
            'productSalesOrders.*.publisher' => 'required',
            'productSalesOrders.*.platform' => 'required',
            'productSalesOrders.*.access_model' => 'required',
            'productSalesOrders.*.title' => 'required',
            'productSalesOrders.*.frequency' => 'required',
            'productSalesOrders.*.subscription_period' => 'required',
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
                'e_issn' => '',
                'editor' => '',
                'publisher' => '',
                'platform' => '',
                'access_model' => '',
                'title' => '',
                'frequency' => '',
                'subscription_period' => '',
                'quantity' => 1,
                'price' => 0,
                'total' => 0,
            ]
        ];

        $this->setEJournals();
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
            $type = Type::where('name', 'e-journals')->first();
            foreach ($this->productSalesOrders as $key => $productSalesOrder) {
                $product = Product::firstOrCreate(
                    ['title' => $productSalesOrder['title']], ['type_id' => $type->id],
                );

                $editor = Editor::firstOrCreate(
                    ['name' => $productSalesOrder['editor']]
                );

                $publisher = Publisher::firstOrCreate(
                    ['name' => $productSalesOrder['publisher']]
                );

                $platform = Platform::firstOrCreate(
                    ['name' => $productSalesOrder['platform']]
                );

                $accessModel = AccessModel::firstOrCreate(
                    ['name' => $productSalesOrder['access_model']]
                );


                $eJournal = EJournal::firstOrCreate(
                    ['product_id' => $product->id], 
                    [
                        'e_issn' => $productSalesOrder['e_issn'], 
                        'editor_id' => $editor->id,
                        'publisher_id' => $publisher->id,
                        'platform_id' => $platform->id,
                        'access_model_id' => $accessModel->id,
                        'frequency' => $productSalesOrder['frequency'],
                        'subscription_period' => $productSalesOrder['subscription_period'],
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

    public function setEJournals()
    {
        $this->books = EJournal::with([
            'product',
        ])
        ->orderBy('created_at', 'DESC')
        ->get();   
    }

    public function setProduct($index, $e_issn)
    {
        $eJournal = EJournal::with([
                'editor',
                'publisher',
                'platform',
                'accessModel',
                'product',
            ])
            ->where('e_issn', $e_issn)->first();

        if($eJournal) {
            $this->productSalesOrders[$index]['editor'] = $eJournal->editor->name ?? null;
            $this->productSalesOrders[$index]['publisher'] = $eJournal->publisher->name ?? null;
            $this->productSalesOrders[$index]['platform'] = $eJournal->platform->name ?? null;
            $this->productSalesOrders[$index]['access_model'] = $eJournal->accessModel->name ?? null;
            $this->productSalesOrders[$index]['title'] = $eJournal->product->title ?? null;
            $this->productSalesOrders[$index]['frequency'] = $eJournal->frequency;
            $this->productSalesOrders[$index]['subscription_period'] = $eJournal->subscription_period;
            $this->productSalesOrders[$index]['price'] = $eJournal->product->price ?? 0;
            $this->productSalesOrders[$index]['quantity'] = $eJournal->product->quantity ?? 0;
            $this->productSalesOrders[$index]['total'] = $this->getPerUnitTotal($eJournal);
            $this->calculate();
        }
    }

    public function getPerUnitTotal($eJournal)
    {
        return ($eJournal->product->price * $eJournal->product->quantity);        
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
            'e_issn' => '',
            'editor' => '',
            'publisher' => '',
            'platform' => '',
            'access_model' => '',
            'title' => '',
            'frequency' => '',
            'subscription_period' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    public function setQuantity($index, $quantity)
    {
        $this->productSalesOrders[$index]['total'] = $this->getEJournalTotal($index, $quantity, 'quantity');
        $this->calculate();
    }

    public function setPrice($index, $price)
    {
        $this->productSalesOrders[$index]['total'] = $this->getEJournalTotal($index, $price, 'price');
        $this->calculate();
    }

    public function getEJournalTotal($index, $value, $type)
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
        return view('livewire.list-e-journal-sales-orders');
    }
}
