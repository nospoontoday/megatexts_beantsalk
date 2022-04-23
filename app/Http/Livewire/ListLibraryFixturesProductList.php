<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ListLibraryFixturesProductList extends Component
{    
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.list-library-fixtures-product-list');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
