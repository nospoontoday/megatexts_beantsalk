<?php

namespace App\Http\Livewire;

use App\Exports\CustomersExport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

use Illuminate\Support\Facades\DB;

class ListCustomers extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-customers', [
            'customers' => Customer::when($this->term, function($query, $term){
                return $query->where(DB::raw('lower(company_name)'), 'LIKE', '%'.strtolower($term).'%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = Customer::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new CustomersExport($this->selectedRows))->download('customers.xlsx');
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        $customer->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Customer deleted',
            'icon'  => 'success',
        ]);
    }
}
