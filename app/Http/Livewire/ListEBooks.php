<?php

namespace App\Http\Livewire;

use App\Exports\EBooksExport;
use App\Models\EBook;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;

class ListEBooks extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-e-books', [
            'eBooks' => EBook::with(
                [
                    'product',
                    'author',
                    'publisher',
                    'platform',
                    'accessModel',
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
                ->orWhereHas('platform', function($query){
                    return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
                })
                ->orWhereHas('accessModel', function($query){
                    return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
                })
                ->orWhere(DB::raw('lower(e_isbn)'), 'LIKE', '%'.strtolower($this->term).'%')
                ->orderBy('created_at', 'DESC')
                ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = EBook::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new EBooksExport($this->selectedRows))->download('ebooks.xlsx');
    }

    public function destroy($id)
    {
        $eBook = EBook::find($id);

        $eBook->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'eBook deleted',
            'icon'  => 'success',
        ]);
    }
}
