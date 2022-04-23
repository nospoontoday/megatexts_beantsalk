<?php

namespace App\Http\Livewire;

use App\Exports\OnlineDatabasesExport;
use App\Models\OnlineDatabase;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;

class ListOnlineDatabases extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-online-databases', [
            'onlineDatabases' => OnlineDatabase::with(
                [
                    'product',
                    'publisher',
                    'platform',
                    'accessModel',
                ]
            )
            ->whereHas('product', function($query){
                return $query->where(DB::raw('lower(title)'), 'LIKE', '%'.strtolower($this->term).'%');
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
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = OnlineDatabase::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new OnlineDatabasesExport($this->selectedRows))->download('onlinedatabases.xlsx');
    }

    public function destroy($id)
    {
        $onlineDatabase = OnlineDatabase::find($id);

        $onlineDatabase->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Online Database deleted',
            'icon'  => 'success',
        ]);
    }
}
