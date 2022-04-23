<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ListPrintBooksProductList extends Component
{
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.list-print-books-product-list');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
