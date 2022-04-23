<?php

namespace App\Http\Livewire;

use App\Exports\LibraryTechnologiesExport;
use App\Models\LibraryTechnology;
use Livewire\Component;

use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ListLibraryTechnologies extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-library-technologies', [
            'libraryTechnologies' => LibraryTechnology::with(
                [
                    'product',
                    'developer',
                ]
            )
            ->whereHas('product', function($query){
                return $query->where(DB::raw('lower(title)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orWhereHas('developer', function($query){
                return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orWhere(DB::raw('lower(item_code)'), 'LIKE', '%'.strtolower($this->term).'%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = LibraryTechnology::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new LibraryTechnologiesExport($this->selectedRows))->download('librarytechnologies.xlsx');
    }

    public function destroy($id)
    {
        $libraryTechnology = LibraryTechnology::find($id);

        $libraryTechnology->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Library Technology deleted',
            'icon'  => 'success',
        ]);
    }
}
