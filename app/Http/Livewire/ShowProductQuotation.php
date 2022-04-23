<?php

namespace App\Http\Livewire;

use App\Models\Quotation;
use Livewire\Component;

class ShowProductQuotation extends Component
{
    public $quotation;
    public $status;
    public $project_title;
    public $pr_number;
    public $deadline;
    public $bidding_date;
    public $purpose_name;
    public $products;
    public $showPrintBooks = false;
    public $showPrintJournals = false;

    public function render()
    {
        return view('livewire.show-product-quotation');
    }

    public function mount($quotation)
    {
        $this->status = $this->processStatus($quotation->status);
        $this->product_title = $quotation->project_title;
        $this->pr_number = $quotation->pr_number;
        $this->deadline = $quotation->deadline;
        $this->bidding_date = $quotation->bidding_date;
        $this->purpose_name = $quotation->purpose->name ?? null;
        $this->products = $quotation->products;
        $this->quotation_id = $quotation->id;

        foreach($this->products as $product) {
            if($product->type_id == 1) {
                $this->showPrintBooks = true;
            }

            if($product->type_id == 2) {
                $this->showPrintJournals = true;
            }
        }
    }

    public function processStatus($status)
    {
        if($status == "pending") {
            return "<h2 class='fw-bolder text-pending'> PENDING </h2>";
        }

        else if($status == "approved") {
            return "<h2 class='fw-bolder text-success'> APPROVED </h2>";
        }

        return "<h2 class='fw-bolder text-danger'> CANCELLED </h2>"; 
    }

    public function updateStatus($status)
    {
        $this->status = $this->processStatus($status);

        $quotation = Quotation::find($this->quotation_id);

        $quotation->status = $status;
        $quotation->save();
    }
}
