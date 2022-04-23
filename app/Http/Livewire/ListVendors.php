<?php

namespace App\Http\Livewire;

use App\Exports\VendorsExport;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListVendors extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-vendors', [
            'vendors' => Vendor::when($this->term, function($query, $term){
                return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($term).'%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = Vendor::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new VendorsExport($this->selectedRows))->download('vendors.xlsx');
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);

        $vendor->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Vendor deleted',
            'icon'  => 'success',
        ]);
    }
}
