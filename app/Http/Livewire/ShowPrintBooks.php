<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ShowPrintBooks extends Component
{
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.show-print-books');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
