<?php

namespace App\Http\Livewire;

use App\Models\PrintBook;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PrintBooksSearch extends Component
{
    public $term;
    public $printBooks;

    public function mount()
    {
        $this->term = '';
        $this->printBooks = [];
    }

    public function updatedQuery()
    {
        $this->printBooks = PrintBook::with([
            'product',
            'author',
            'publisher',
        ])
        ->whereHas('product', function($query){
            return $query->where(DB::raw('lower(title)'), 'LIKE', '%'.strtolower($this->term).'%');
        })
        ->orWhereHas('author', function($query){
            return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
        })
        ->orWhereHas('publisher', function($query){
            return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
        })
        ->orWhere(DB::raw('lower(isbn_13)'), 'LIKE', '%'.strtolower($this->term).'%')
        ->orderBy('created_at', 'DESC')
        ->get()
        ->toArray(); 
    }

    public function render()
    {
        return view('livewire.print-books-search');
    }
}
