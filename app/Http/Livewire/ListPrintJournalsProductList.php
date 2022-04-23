<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ListPrintJournalsProductList extends Component
{
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.list-print-journals-product-list');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
