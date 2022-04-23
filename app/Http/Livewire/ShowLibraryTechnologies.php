<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ShowLibraryTechnologies extends Component
{
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.show-library-technologies');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
