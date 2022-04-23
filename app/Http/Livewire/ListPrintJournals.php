<?php

namespace App\Http\Livewire;

use App\Exports\PrintJournalsExport;
use App\Models\PrintJournal;
use Livewire\WithPagination;
use Livewire\Component;

use Illuminate\Support\Facades\DB;

class ListPrintJournals extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-print-journals', [
            'printJournals' => PrintJournal::with(
                [
                    'product',
                    'editor',
                ]
            )
            ->whereHas('product', function($query){
                return $query->where(DB::raw('lower(title)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orWhereHas('editor', function($query){
                return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orWhere(DB::raw('lower(issn)'), 'LIKE', '%'.strtolower($this->term).'%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = PrintJournal::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new PrintJournalsExport($this->selectedRows))->download('printjournals.xlsx');
    }

    public function destroy($id)
    {
        $printJournal = PrintJournal::find($id);
        if($printJournal->product->is_quotation == 0 && $printJournal->product->is_vendor == 0) {
            $printJournal->delete();

            $this->dispatchBrowserEvent('alert', [
                'title' => 'Print Journal deleted',
                'icon'  => 'success',
            ]);
        }
    }
}
