<?php

namespace App\Http\Livewire;

use App\Models\SalesOrder;
use Livewire\Component;

class ShowSalesOrder extends Component
{
    public $showSendButton = false;

    public $salesOrder;

    public $status;
    public $companyName;
    public $address;
    public $city;
    public $state;
    public $email;
    public $contactNumber;
    public $buyerName;
    public $branchName;
    public $date;
    public $iorfNumber;
    public $poNumber;
    public $total;
    public $salesRep;
    public $products;
    public $showPrintBooks = false;
    public $showPrintJournals = false;
    public $showAvMaterials = false;
    public $showLibraryFixtures = false;
    public $showEBooks = false;
    public $showEJournals = false;
    public $showOnlineDatabases = false;
    public $showLibraryTechnologies = false;

    public function mount($salesOrder)
    {
        $this->status = $this->processStatus($salesOrder->status);
        $this->showSendButton = ($this->salesOrder->status == "topack")
            ? true
            : false;
        $this->companyName = $salesOrder->customer->company_name ?? null;
        $this->address = $salesOrder->customer->presentAddress->present_address ?? null;
        $this->city = $salesOrder->customer->presentAddress->city ?? null;
        $this->state = $salesOrder->customer->presentAddress->state ?? null;
        $this->email = $salesOrder->customer->presentAddress->email ?? null;
        $this->contactNumber = $salesOrder->customer->contact->mobile ?? null;
        $this->buyerName = $salesOrder->customer->buyer_name ?? null;
        $this->branchName = $salesOrder->branch->name ?? null;
        $this->date = $salesOrder->created_at->format("m-d-Y");
        $this->iorfNumber = $salesOrder->iorf_number;
        $this->poNumber = $salesOrder->reference_number;
        $this->total = $salesOrder->total_amount;
        $this->salesRep = $salesOrder->sales_rep_full_name;
        $this->products = $salesOrder->products;
        
        foreach($this->products as $product) {
            if($product->type_id == 1) {
                $this->showPrintBooks = true;
            }

            if($product->type_id == 2) {
                $this->showPrintJournals = true;
            }

            if($product->type_id == 3) {
                $this->showAvMaterials = true;
            }

            if($product->type_id == 4) {
                $this->showLibraryFixtures = true;
            }

            if($product->type_id == 5) {
                $this->showEBooks = true;   
            }

            if($product->type_id == 6) {
                $this->showEJournals = true;
            }

            if($product->type_id == 7) {
                $this->showOnlineDatabases = true;
            }

            if($product->type_id == 8) {
                $this->showLibraryTechnologies = true;
            }
        }

    }

    public function render()
    {
        return view('livewire.show-sales-order');
    }

    public function processStatus($status)
    {
        if($status == "topack") {
            return "<h2 class='fw-bolder text-danger'> TO PACK </h2>";
        }

        else if($status == "packed") {
            return "<h2 class='fw-bolder text-success'> PACKED </h2>";
        }

        else if($status == "pending") {
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
        $this->showSendButton = true;

        $this->salesOrder->status = $status;

        $this->salesOrder->save();
        $this->salesOrder->fresh();
    }
}
