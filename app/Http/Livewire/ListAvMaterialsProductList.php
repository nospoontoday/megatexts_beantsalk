<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ListAvMaterialsProductList extends Component
{
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.list-av-materials-product-list');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
