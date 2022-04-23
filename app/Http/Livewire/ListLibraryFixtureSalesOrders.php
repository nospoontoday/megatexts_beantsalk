<?php

namespace App\Http\Livewire;

use App\Models\LibraryFixture;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Type;
use App\Models\User;

class ListLibraryFixtureSalesOrders extends AbstractListProductsSalesOrders
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
            'productSalesOrders.*.manufacturer' => 'required',
            'productSalesOrders.*.title' => 'required',
            'productSalesOrders.*.dimension' => 'required',
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
                'manufacturer' => '',
                'title' => '',
                'dimension' => '',
                'quantity' => 1,
                'price' => 0,
                'total' => 0,
            ]
        ];

        $this->setLibraryFixtures();
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
            $type = Type::where('name', 'library-fixtures')->first();
            foreach ($this->productSalesOrders as $key => $productSalesOrder) {
                $product = Product::firstOrCreate(
                    ['title' => $productSalesOrder['title']], ['type_id' => $type->id],
                );

                $manufacturer = Manufacturer::firstOrCreate(
                    ['name' => $productSalesOrder['manufacturer']]
                );

                $libraryFixture = LibraryFixture::firstOrCreate(
                    ['product_id' => $product->id], 
                    [
                        'item_code' => $productSalesOrder['item_code'], 
                        'manufacturer' => $manufacturer->id,
                        'dimension' => $productSalesOrder['dimension'],
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

    public function setLibraryFixtures()
    {
        $this->books = LibraryFixture::with([
            'product',
        ])
        ->orderBy('created_at', 'DESC')
        ->get();   
    }

    public function setProduct($index, $item_code)
    {
        $libraryFixture = LibraryFixture::with([
                'manufacturer',
                'product',
            ])
            ->where('item_code', $item_code)->first();

        if($libraryFixture) {
            $this->productSalesOrders[$index]['manufacturer'] = $libraryFixture->manufacturer->name ?? null;
            $this->productSalesOrders[$index]['title'] = $libraryFixture->product->title ?? null;
            $this->productSalesOrders[$index]['dimension'] = $libraryFixture->dimension;
            $this->productSalesOrders[$index]['price'] = $libraryFixture->product->price ?? 0;
            $this->productSalesOrders[$index]['quantity'] = $libraryFixture->product->quantity ?? 0;
            $this->productSalesOrders[$index]['total'] = $this->getPerUnitTotal($libraryFixture);
            $this->calculate();
        }
    }

    public function getPerUnitTotal($libraryFixture)
    {
        return ($libraryFixture->product->price * $libraryFixture->product->quantity);        
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
            'manufacturer' => '',
            'title' => '',
            'dimension' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    public function setQuantity($index, $quantity)
    {
        $this->productSalesOrders[$index]['total'] = $this->getLibraryFixtureTotal($index, $quantity, 'quantity');
        $this->calculate();
    }

    public function setPrice($index, $price)
    {
        $this->productSalesOrders[$index]['total'] = $this->getLibraryFixtureTotal($index, $price, 'price');
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
        return view('livewire.list-library-fixture-sales-orders');
    }
}
