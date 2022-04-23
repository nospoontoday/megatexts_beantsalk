<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

abstract class AbstractListProductsSalesOrders extends Component
{
    public function removeProduct($index)
    {
        unset($this->productSalesOrders[$index]);
        $this->productSalesOrders = array_values($this->productSalesOrders);
        $this->calculate();
    }

    public function updatedBranch()
    {
        $this->salesReps = User::role('Sales Representative')
            ->with('branches')
            ->whereHas('branches', function($query){
                $query->where('branches.id', $this->branch);
            })
            ->get();

        if($this->salesReps) {
            $this->salesRep = auth()->user()->id;
        }
    }

    public function updatedCustomer()
    {
        $this->customers = Customer::with([
            'presentAddress',
            'contact',
        ])
        ->where(DB::raw('lower(company_name)'), 'LIKE', '%'.strtolower($this->customer).'%')
        ->orderBy('created_at', 'DESC')
        ->take(2)
        ->get();        
    }

    public function chooseCustomer($customerId)
    {
        $customer = Customer::find($customerId);

        $this->customer = $customer->company_name;
        $this->address = $customer->presentAddress->present_address ?? null;
        $this->city = $customer->presentAddress->city ?? null;
        $this->state = $customer->presentAddress->state ?? null;
        $this->email = $customer->presentAddress->email ?? null;
        $this->contact = $customer->contact->mobile ?? null;
        $this->buyer = $customer->buyer_name;

        $this->customers = [];
    }

    public function productsHaveDuplicates() {
        foreach($this->productSalesOrders as $key => $productSalesOrder) {
            foreach($this->productSalesOrders as $search_key => $search_array){
                if($search_array['title'] == $productSalesOrder['title']) {
                    if($search_key != $key) {
                        $error = \Illuminate\Validation\ValidationException::withMessages([
                            'productSalesOrders['.$key.'][title]' => ['Duplicates found in products'],
                        ]);
                        throw $error;                        
                    }
                }
            }
        }
    }

    public function salesOrderHasAtLeastOneProduct()
    {
        if(count($this->productSalesOrders) < 1) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'error' => ['Sales Order must at least have 1 Product'],
            ]);
            throw $error;            
        }
    }

    public function createSalesOrder()
    {
        $customer = Customer::where('company_name', $this->customer)->first();

        if(! $customer) {
            $customer = Customer::create([
                'company_name' => $this->customer,
                'buyer_name' => $this->buyer,
            ]);

            $customer->addresses()->create(
                [
                    'present_address' => $this->address,
                    'email' => $this->email,
                    'city' => $this->city,
                    'state' => $this->state,
                ]
            );

            $customer->contacts()->create([
                'mobile' => $this->contact,
            ]);
        }
    
        $salesOrder = SalesOrder::create([
            'customer_id' => $customer->id,
            'branch_id' => $this->branch,
            'order_summary' => count($this->productSalesOrders),
            'date' => $this->date,
            'total_amount' => 0,
            'reference_number' => $this->poNumber,
            'iorf_number' => $this->iorf,
            'user_id' => $this->salesRep,
        ]);
        
        return $salesOrder;        
    }

}