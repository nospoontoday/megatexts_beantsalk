<?php

namespace App\Http\Livewire;

use App\Exports\LibraryFixturesExport;
use App\Models\LibraryFixture;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListLibraryFixtures extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-library-fixtures', [
            'libraryFixtures' => LibraryFixture::with(
                [
                    'product',
                    'manufacturer',
                ]
            )
            ->whereHas('product', function($query){
                return $query->where(DB::raw('lower(title)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orWhereHas('manufacturer', function($query){
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
			$this->selectedRows = LibraryFixture::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new LibraryFixturesExport($this->selectedRows))->download('libraryfixtures.xlsx');
    }

    public function destroy($id)
    {
        $libraryFixture = LibraryFixture::find($id);

        $libraryFixture->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Library Fixture deleted',
            'icon'  => 'success',
        ]);
    }
}
