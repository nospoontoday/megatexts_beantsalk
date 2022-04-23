<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ShowAvMaterials extends Component
{
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.show-av-materials');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
