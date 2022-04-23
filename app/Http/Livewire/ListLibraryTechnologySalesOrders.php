<?php

namespace App\Http\Livewire;

use App\Models\Developer;
use App\Models\LibraryTechnology;
use App\Models\Product;
use App\Models\Type;
use App\Models\User;

class ListLibraryTechnologySalesOrders extends AbstractListProductsSalesOrders
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
            'productSalesOrders.*.item_code' => 'required',
            'productSalesOrders.*.developer' => 'required',
            'productSalesOrders.*.title' => 'required',
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
                'item_code' => '',
                'editor' => '',
                'title' => '',
                'subscription_period' => '',
                'quantity' => 1,
                'price' => 0,
                'total' => 0,
            ]
        ];

        $this->setLibraryTechnologies();
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
            $type = Type::where('name', 'library-technologies')->first();
            foreach ($this->productSalesOrders as $key => $productSalesOrder) {
                $product = Product::firstOrCreate(
                    ['title' => $productSalesOrder['title']], ['type_id' => $type->id],
                );

                $developer = Developer::firstOrCreate(
                    ['name' => $productSalesOrder['developer']]
                );



                $libraryTechnology = LibraryTechnology::firstOrCreate(
                    ['product_id' => $product->id], 
                    [
                        'item_code' => $productSalesOrder['item_code'], 
                        'developer' => $developer->id,
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

    public function setLibraryTechnologies()
    {
        $this->books = LibraryTechnology::with([
            'product',
        ])
        ->orderBy('created_at', 'DESC')
        ->get();   
    }

    public function setProduct($index, $item_code)
    {
        $libraryTechnology = LibraryTechnology::with([
                'developer',
                'product',
            ])
            ->where('item_code', $item_code)->first();

        if($libraryTechnology) {
            $this->productSalesOrders[$index]['developer'] = $libraryTechnology->developer->name ?? null;
            $this->productSalesOrders[$index]['title'] = $libraryTechnology->product->title ?? null;
            $this->productSalesOrders[$index]['subscription_period'] = $libraryTechnology->subscription_period;
            $this->productSalesOrders[$index]['price'] = $libraryTechnology->product->price ?? 0;
            $this->productSalesOrders[$index]['quantity'] = $libraryTechnology->product->quantity ?? 0;
            $this->productSalesOrders[$index]['total'] = $this->getPerUnitTotal($libraryTechnology);
            $this->calculate();
        }
    }

    public function getPerUnitTotal($libraryTechnology)
    {
        return ($libraryTechnology->product->price * $libraryTechnology->product->quantity);        
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
            'item_code' => '',
            'developer' => '',
            'title' => '',
            'subscription_period' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    public function setQuantity($index, $quantity)
    {
        $this->productSalesOrders[$index]['total'] = $this->getLibraryTechnologyTotal($index, $quantity, 'quantity');
        $this->calculate();
    }

    public function setPrice($index, $price)
    {
        $this->productSalesOrders[$index]['total'] = $this->getLibraryTechnologyTotal($index, $price, 'price');
        $this->calculate();
    }

    public function getLibraryTechnologyTotal($index, $value, $type)
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
        return view('livewire.list-library-technology-sales-orders');
    }
}
