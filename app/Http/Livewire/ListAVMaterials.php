<?php

namespace App\Http\Livewire;

use App\Exports\AVMaterialsExport;
use App\Models\AVMaterial;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ListAVMaterials extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $term;

    public $selectedRows = [];

	public $selectPageRows = false;

    public function render()
    {
        return view('livewire.list-a-v-materials', [
            'avMaterials' => AVMaterial::with(
                [
                    'product',
                    'author',
                    'publisher',
                ]
            )
            ->whereHas('product', function($query){
                return $query->where(DB::raw('lower(title)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orWhereHas('author', function($query){
                return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orWhereHas('publisher', function($query){
                return $query->where(DB::raw('lower(name)'), 'LIKE', '%'.strtolower($this->term).'%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
        ]);
    }

    public function updatedSelectPageRows($value)
	{
		if ($value) {
			$this->selectedRows = AVMaterial::pluck('id')->map(function ($id) {
				return (string) $id;
			});
		} else {
			$this->reset(['selectedRows', 'selectPageRows']);
		}
	}

    public function export()
    {
        return (new AVMaterialsExport($this->selectedRows))->download('avmaterials.xlsx');
    }

    public function destroy($id)
    {
        $avMaterial = AVMaterial::find($id);

        $avMaterial->delete();

        $this->dispatchBrowserEvent('alert', [
            'title' => 'AV MAterial deleted',
            'icon'  => 'success',
        ]);
    }
}
