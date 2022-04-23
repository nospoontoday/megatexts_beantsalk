<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SalesOrder;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class ListSalesOrders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public $status = 'pending';

    public $type = 'print-books';

    protected $queryString = ['status', 'type'];

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function getSalesOrders($status)
    {
        $type = Type::where('name', $this->type)->first();

        $salesOrderQuery = SalesOrder::query();

        ($status == 'approved')
            ? $salesOrderQuery->whereIn('status', ['topack', 'packed', 'delivered', 'served'])
            : $salesOrderQuery->where('status', $status);


        return $salesOrderQuery->with(
                [
                    'customer',
                    'customer.presentAddress',
                    'customer.contact',
                    'products',
                ]
            )
            ->whereHas('customer', function($query){
                return $query->where(DB::raw('lower(company_name)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->whereHas('products', function($query) use ($type) {
                return $query->where('type_id', $type->id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ;
    }

    public function render()
    {
        $salesOrders = $this->getSalesOrders($this->status);

        return view('livewire.list-sales-orders', compact('salesOrders'));
    }
}
