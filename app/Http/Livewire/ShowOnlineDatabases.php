<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ShowOnlineDatabases extends Component
{
    use WithPagination;

    public $products;

    public function render()
    {
        return view('livewire.show-online-databases');
    }

    public function mount($products)
    {
        $this->products = $products;
    }
}
