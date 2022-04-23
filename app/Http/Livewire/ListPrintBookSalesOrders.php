<?php

namespace App\Http\Livewire;

use App\Models\Author;
use App\Models\PrintBook;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Type;
use App\Models\User;

class ListPrintBookSalesOrders extends AbstractListProductsSalesOrders
{
    public $productSalesOrders = [];

    public $branches;

    public $customer;
    public $isbn;

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
            'productSalesOrders.*.isbn' => 'required',
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
            $type = Type::where('name', 'print-books')->first();
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

                $printBook = PrintBook::firstOrCreate(
                    ['product_id' => $product->id], 
                    [
                        'isbn_13' => $productSalesOrder['isbn'], 
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
            'isbn' => '',
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
        $this->productSalesOrders[$index]['total'] = $this->getPrintBookTotal($index, $quantity, 'quantity');
        $this->calculate();
    }

    public function setPrice($index, $price)
    {
        $this->productSalesOrders[$index]['total'] = $this->getPrintBookTotal($index, $price, 'price');
        $this->calculate();
    }

    public function setDiscount($index, $discount)
    {
        $this->productSalesOrders[$index]['total'] = $this->getPrintBookTotal($index, $discount, 'discount');
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

    public function getPrintBookTotal($index, $value, $type)
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

    public function setProduct($index, $isbn)
    {
        $printBook = PrintBook::with([
                'author',
                'publisher',
                'product',
            ])
            ->where('isbn_13', $isbn)->first();

        if($printBook) {
            $this->productSalesOrders[$index]['author'] = $printBook->author->name ?? null;
            $this->productSalesOrders[$index]['publisher'] = $printBook->publisher->name ?? null;
            $this->productSalesOrders[$index]['title'] = $printBook->product->title ?? null;
            $this->productSalesOrders[$index]['publication_year'] = $printBook->publication_year;
            $this->productSalesOrders[$index]['price'] = $printBook->product->price ?? 0;
            $this->productSalesOrders[$index]['discount'] = $printBook->discount;
            $this->productSalesOrders[$index]['quantity'] = $printBook->product->quantity ?? 0;
            $this->productSalesOrders[$index]['total'] = $this->getPerUnitTotal($printBook);
            $this->calculate();
        }
    }

    public function getPerUnitTotal($printBook)
    {
        return ($printBook->product->price * $printBook->product->quantity) - $printBook->discount;        
    }

    public function setPrintBooks()
    {
        $this->books = PrintBook::with([
            'product',
        ])
        ->orderBy('created_at', 'DESC')
        ->get();   
    }    
    
    public function mount($branches)
    {
        $this->setPrintBooks();
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
                'isbn' => '',
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
        return view('livewire.list-print-book-sales-orders');
    }
}
