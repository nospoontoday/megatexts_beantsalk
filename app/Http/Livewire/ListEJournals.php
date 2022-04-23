<?php

namespace App\Http\Livewire;

use App\Exports\EJournalsExport;
use App\Models\EJournal;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;

class ListEJournals extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-e-journals', [
            'eJournals' => EJournal::with(
                [
                    'product',
                    'editor',
                    'publisher',
                    'platform',
                    'accessModel',
                ])
                ->whereHas('product', function($query){
                    return $query->where(DB::raw('lower(title)'), 'LIKE', '%'.strtolower($this->term).'%');
                })
                ->orWhereHas('editor', function($query){
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
                ->orWhere(DB::raw('lower(e_issn)'), 'LIKE', '%'.strtolower($this->term).'%')
                ->orderBy('created_at', 'DESC')
                ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = EJournal::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new EJournalsExport($this->selectedRows))->download('ejournals.xlsx');
    }

    public function destroy($id)
    {
        $eJournal = EJournal::find($id);

        $eJournal->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'eJournal deleted',
            'icon'  => 'success',
        ]);
    }
}
