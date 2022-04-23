<?php

namespace App\Http\Livewire;

use App\Exports\PrintBooksExport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PrintBook;

use Illuminate\Support\Facades\DB;

class ListPrintBooks extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-print-books', [
            'printBooks' => PrintBook::with([
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
            ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = PrintBook::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new PrintBooksExport($this->selectedRows))->download('printbooks.xlsx');
    }

    public function destroy($id)
    {
        $printBook = PrintBook::find($id);

        if($printBook->product->is_quotation == 0 && $printBook->product->is_vendor == 0) {
            $printBook->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Print Book deleted',
                'icon'  => 'success',
            ]);
        }

    }
}
