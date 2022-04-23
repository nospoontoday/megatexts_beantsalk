<?php

namespace App\Http\Livewire;

use App\Models\AccessModel;
use App\Models\Author;
use App\Models\EBook;
use App\Models\Platform;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Type;
use App\Models\User;

class ListEBookSalesOrders extends AbstractListProductsSalesOrders
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
            'productSalesOrders.*.e_isbn' => 'required',
            'productSalesOrders.*.author' => 'required',
            'productSalesOrders.*.publisher' => 'required',
            'productSalesOrders.*.platform' => 'required',
            'productSalesOrders.*.access_model' => 'required',
            'productSalesOrders.*.title' => 'required',
            'productSalesOrders.*.publication_year' => 'required',
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
                'e_isbn' => '',
                'author' => '',
                'publisher' => '',
                'platform' => '',
                'access_model' => '',
                'title' => '',
                'publication_year' => '',
                'quantity' => 1,
                'price' => 0,
                'total' => 0,
            ]
        ];

        $this->setEBooks();
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
            $type = Type::where('name', 'e-books')->first();
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

                $platform = Platform::firstOrCreate(
                    ['name' => $productSalesOrder['platform']]
                );

                $accessModel = AccessModel::firstOrCreate(
                    ['name' => $productSalesOrder['access_model']]
                );


                $eBook = EBook::firstOrCreate(
                    ['product_id' => $product->id], 
                    [
                        'e_isbn' => $productSalesOrder['e_isbn'], 
                        'author_id' => $author->id,
                        'publisher_id' => $publisher->id,
                        'platform_id' => $platform->id,
                        'access_model_id' => $accessModel->id,
                        'publication_year' => $productSalesOrder['publication_year'],
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

    public function setEBooks()
    {
        $this->books = EBook::with([
            'product',
        ])
        ->orderBy('created_at', 'DESC')
        ->get();   
    }

    public function setProduct($index, $e_isbn)
    {
        $eBook = EBook::with([
                'author',
                'publisher',
                'platform',
                'accessModel',
                'product',
            ])
            ->where('e_isbn', $e_isbn)->first();

        if($eBook) {
            $this->productSalesOrders[$index]['author'] = $eBook->author->name ?? null;
            $this->productSalesOrders[$index]['publisher'] = $eBook->publisher->name ?? null;
            $this->productSalesOrders[$index]['platform'] = $eBook->platform->name ?? null;
            $this->productSalesOrders[$index]['access_model'] = $eBook->accessModel->name ?? null;
            $this->productSalesOrders[$index]['title'] = $eBook->product->title ?? null;
            $this->productSalesOrders[$index]['publication_year'] = $eBook->publication_year;
            $this->productSalesOrders[$index]['price'] = $eBook->product->price ?? 0;
            $this->productSalesOrders[$index]['quantity'] = $eBook->product->quantity ?? 0;
            $this->productSalesOrders[$index]['total'] = $this->getPerUnitTotal($eBook);
            $this->calculate();
        }
    }

    public function getPerUnitTotal($eBook)
    {
        return ($eBook->product->price * $eBook->product->quantity);        
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
            'e_isbn' => '',
            'author' => '',
            'publisher' => '',
            'platform' => '',
            'access_model' => '',
            'title' => '',
            'publication_year' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    public function setQuantity($index, $quantity)
    {
        $this->productSalesOrders[$index]['total'] = $this->getEBookTotal($index, $quantity, 'quantity');
        $this->calculate();
    }

    public function setPrice($index, $price)
    {
        $this->productSalesOrders[$index]['total'] = $this->getEBookTotal($index, $price, 'price');
        $this->calculate();
    }

    public function getEBookTotal($index, $value, $type)
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
        return view('livewire.list-e-book-sales-orders');
    }
}
