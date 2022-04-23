<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Type;
use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class ListProductList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $products;

    public $types;

    public $selectedType;

    public function rules()
    {
        return [
            'selectedType' => 'required',
        ];
    }

    public function mount()
    {
        $this->selectedType = Type::where('name', 'print-books')
            ->first()->id;

        $this->products = $this->setProducts($this->selectedType);
        $this->types = Type::get();
    }

    public function updateProductList()
    {
        $this->products = $this->setProducts($this->selectedType);
    }

    public function setProducts($typeId)
    {
        return Product::where('type_id', $typeId)
            ->with(
                [
                    'printBook',
                    'printBook.author',
                    'printBook.publisher',
                    'printJournal',
                    'printJournal.editor',
                    'avMaterial',
                    'avMaterial.author',
                    'avMaterial.publisher',
                    'vendors',
                ]
            )
            ->whereHas('vendors')
            ->get();
    }

    public function render()
    {
        return view('livewire.list-product-list');
    }
}
