<?php

namespace App\Http\Livewire;

use App\Models\Author;
use App\Models\AVMaterial;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Type;
use App\Models\User;

class ListAvMaterialSalesOrders extends AbstractListProductsSalesOrders
{
    public $productSalesOrders = [];

    public $branches;

    public $customer;
    public $item_code;

    public $address;
    public $date;
    public $city;
    public $state;
    public $email;
    public $contact;
    public $buyer;
    public $branch;
    public $iorf;
    public $salesReps;
    public $salesRep;
    public $poNumber;

    public $author;
    public $publisher;

    public $customers;

    public $subTotal;
    public $totalDiscount;
    public $total;
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
            'productSalesOrders.*.author' => 'required',
            'productSalesOrders.*.publisher' => 'required',
            'productSalesOrders.*.title' => 'required',
            'productSalesOrders.*.publication_year' => 'required',
            'productSalesOrders.*.quantity' => 'required',
            'productSalesOrders.*.price' => 'required',
            'productSalesOrders.*.discount' => 'required',
        ];
    }

    public function confirmIfAccurateSummary()
    {
        $subTotal = 0;
        $totalDiscount = 0;
        $total = 0;
        foreach($this->productSalesOrders as $productSalesOrder) {
            $subTotal += $productSalesOrder['total'] + $productSalesOrder['discount'];
            $totalDiscount += $productSalesOrder['discount'];
        }

        $total = $subTotal - $totalDiscount;

        if($this->subTotal != $subTotal) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'error' => ['Sub total is not accurate'],
            ]);
            throw $error;               
        }

        if($this->totalDiscount != $totalDiscount) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'error' => ['Discount is not accurate'],
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

    public function submit()
    {
        $this->validate();

        $this->productsHaveDuplicates();

        $this->salesOrderHasAtLeastOneProduct();

        $this->confirmIfAccurateSummary();

        $salesOrder = $this->createSalesOrder();

        if(!empty($this->productSalesOrders)) {
            $type = Type::where('name', 'AV-materials')->first();
            foreach ($this->productSalesOrders as $key => $productSalesOrder) {
                $product = Product::firstOrCreate(
                    ['title' => $productSalesOrder['title']], ['type_id' => $type->id],
                );

                $author = Author::firstOrCreate(
                    ['name' => $productSalesOrder['author']]
                );

                $publisher = Publisher::firstOrCreate(
                    ['name' => $productSalesOrder['publisher']]
                );

                $AVMaterial = AVMaterial::firstOrCreate(
                    ['product_id' => $product->id], 
                    [
                        'item_code' => $productSalesOrder['item_code'], 
                        'author_id' => $author->id,
                        'publisher_id' => $publisher->id,
                        'publication_year' => $productSalesOrder['publication_year'],
                        'discount' => $productSalesOrder['discount'],
                    ],
                );

                $salesOrder->products()->attach($product->id,
                    [
                        'quantity' => $productSalesOrder['quantity'],
                        'price' => $productSalesOrder['price'],
                        'discount' => $productSalesOrder['discount'],
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

    public function addProduct()
    {
        $this->productSalesOrders[] = [
            'item_code' => '',
            'author' => '',
            'publisher' => '',
            'title' => '',
            'publication_year' => '',
            'quantity' => 1,
            'price' => 0,
            'discount' => 0,
            'total' => 0,
        ];
    }

    public function setQuantity($index, $quantity)
    {
        $this->productSalesOrders[$index]['total'] = $this->getAVMaterialTotal($index, $quantity, 'quantity');
        $this->calculate();
    }

    public function setPrice($index, $price)
    {
        $this->productSalesOrders[$index]['total'] = $this->getAVMaterialTotal($index, $price, 'price');
        $this->calculate();
    }

    public function setDiscount($index, $discount)
    {
        $this->productSalesOrders[$index]['total'] = $this->getAVMaterialTotal($index, $discount, 'discount');
        $this->calculate();
    }

    public function calculate()
    {
        $this->totalDiscount = $this->getTotalDiscount();
        $this->subTotal = $this->getSubTotal();
        $this->total = $this->getOverallTotal();
    }

    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach($this->productSalesOrders as $productSalesOrder) {
            $totalDiscount += $productSalesOrder['discount'];
        }

        return $totalDiscount;
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->productSalesOrders as $productSalesOrder) {
            $subTotal += $productSalesOrder['total'] + $productSalesOrder['discount'];
        }

        return $subTotal;
    }

    public function getOverallTotal()
    {   
        return $this->subTotal - $this->totalDiscount;
    }

    public function getAVMaterialTotal($index, $value, $type)
    {
        $quantity = $type == 'quantity'
            ? $value
            : $this->productSalesOrders[$index]['quantity'];

        $price = $type == 'price'
            ? $value
            : $this->productSalesOrders[$index]['price'];

        $discount = $type == 'discount'
            ? $value
            : $this->productSalesOrders[$index]['discount'];

        return ($price * $quantity) - $discount;
    }

    public function setProduct($index, $item_code)
    {
        $AVMaterial = AVMaterial::with([
                'author',
                'publisher',
                'product',
            ])
            ->where('item_code', $item_code)->first();

        if($AVMaterial) {
            $this->productSalesOrders[$index]['author'] = $AVMaterial->author->name ?? null;
            $this->productSalesOrders[$index]['publisher'] = $AVMaterial->publisher->name ?? null;
            $this->productSalesOrders[$index]['title'] = $AVMaterial->product->title ?? null;
            $this->productSalesOrders[$index]['publication_year'] = $AVMaterial->publication_year;
            $this->productSalesOrders[$index]['price'] = $AVMaterial->product->price ?? 0;
            $this->productSalesOrders[$index]['discount'] = $AVMaterial->discount;
            $this->productSalesOrders[$index]['quantity'] = $AVMaterial->product->quantity ?? 0;
            $this->productSalesOrders[$index]['total'] = $this->getPerUnitTotal($AVMaterial);
            $this->calculate();
        }
    }

    public function getPerUnitTotal($AVMaterial)
    {
        return ($AVMaterial->product->price * $AVMaterial->product->quantity) - $AVMaterial->discount;        
    }

    public function setAVMaterials()
    {
        $this->books = AVMaterial::with([
            'product',
        ])
        ->orderBy('created_at', 'DESC')
        ->get();   
    }    
    
    public function mount($branches)
    {
        $this->setAVMaterials();
        $this->customer = '';
        $this->branches = $branches;
        $this->customers = [];
        $this->salesReps = [];
        $this->productVendors = [];
        $this->branch = auth()->user()->branches->first()->id;
        $this->subTotal = 0;
        $this->totalDiscount = 0;
        $this->total = 0;

        $this->productSalesOrders = [
            [
                'item_code' => '',
                'author' => '',
                'publisher' => '',
                'title' => '',
                'publication_year' => '',
                'quantity' => 1,
                'price' => 0,
                'discount' => 0,
                'total' => 0,
            ]
        ];

        $this->salesReps = User::role('Sales Representative')
            ->with('branches')
            ->whereHas('branches', function($query){
                $query->where('branches.id', $this->branch);
            })
            ->get();

        $this->salesRep = auth()->user()->id;
    }

    public function render()
    {
        return view('livewire.list-av-material-sales-orders');
    }
}
