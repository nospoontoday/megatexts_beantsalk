<?php

namespace App\Http\Livewire;

use App\Models\Quotation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListQuotations extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-quotations', 
            [
                'quotations' => Quotation::where('status', 'pending')
                    ->when($this->term, function($query, $term)
                    {
                        return $query
                            ->where(DB::raw('lower(project_title)'), 'LIKE', '%'.strtolower($term).'%')
                            ->orWhere(DB::raw('lower(pr_number)'), 'LIKE', '%'.strtolower($term).'%')
                        ;
                    })
                    ->orderBy('created_at', 'DESC')
                    ->paginate(10)
            ]
        );
    }

    public function destroy($id)
    {
        $quotation = Quotation::find($id);

        $quotation->products()->detach();

        $quotation->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'Quotation deleted',
            'icon'  => 'success',
        ]);
    }
}
