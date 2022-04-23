<div>
    <div class="clearfix">
        <div class="float-start mb-4 me-3">
            <input 

                type="text" 

                class="form-control rounded-pill bg-white"

                wire:model="term"
            />
        </div>
        <div class="float-start mb-4">
            <button type="button"  class="btn btn-info rounded-pill"> Filter</button>
            @if($selectedRows)
            <a wire:click.prevent="export" class="btn btn-primary rounded-pill" href="#"><i class="bi bi-arrow-down"></i>Export</a>
            <span class="ml-2">selected {{ count($selectedRows) }} {{ Str::plural('Vendor', count($selectedRows)) }}</span>
            @endif
        </div>
        <div class="float-end mb-4">
            <a href="{{route('customers.create')}}" type="button"  class="btn btn-primary rounded-pill"><i class="bi bi-plus"></i> Add Customer</a>
            <button data-bs-toggle="modal" data-bs-target="#import" class="btn btn-primary rounded-pill"><i class="bi bi-arrow-down"></i> Import</button>
        </div>
    </div>
    @if (session()->has('failures'))
    <div class="alert alert-success">
        <p>Customers partially imported.</p>
    </div>
    <table class="table table-danger">
        <tr>
            <th>Row</th>
            <th>Attribute</th>
            <th>Errors</th>
            <th>Value</th>
        </tr>

        @foreach (session()->get('failures') as $validation)
            <tr>
                <td>{{ $validation->row() }}</td>
                <td>{{ $validation->attribute() }}</td>
                <td>
                    <ul>
                        @foreach ($validation->errors() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    {{ $validation->values()[$validation->attribute()] }}
                </td>
            </tr>
        @endforeach
    </table>

    @endif
   
    <div class="table-responsive">
        <table class="table text-center">
            <thead class="bg-info ">
                <tr>
                    <th><input class="form-check-input" type="checkbox" value=""></th>
                    <th>Company Name</th>
                    <th>Buyer Name</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>City</th>
                    <th width="280px">Actions</th>
                </tr>
            </thead>
            
        @foreach ($customers as $key => $customer)
        <tr>
            <td>
                <input wire:model="selectPageRows" type="checkbox" value="{{ $customer->id }}" name="customer_ids" id="{{ $customer->id }}">
                <label for="{{ $customer->id }}"></label>
            </td>
            <td>{{ $customer->company_name }}</td>
            <td>{{ $customer->buyer_name }}</td>
            <td>{{ isset($customer->contact) ? $customer->contact->mobile : null }}</td>
            <td>{{ isset($customer->presentAddress) ? $customer->presentAddress->email : null }}</td>
            <td>{{ isset($customer->presentAddress) ? $customer->presentAddress->city : null }}</td>
            <td>
                <div class="btn-group">
                    <button 
                        type="button" 
                        class="btn bg-light dropdown-toggle" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">

                            Action
                        
                        </button>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Export</a></li>
                        <li><a class="dropdown-item"  href="{{ route('customers.edit',$customer->id) }}">Edit</a></li>
                        <li><a class="dropdown-item" href="#" wire:click="destroy({{ $customer->id }})" >Delete</a></li>
                        </ul>
                    </div>                             
                </td>
            </tr>
            @endforeach
        </table>
    <div class="pt-2">
        {{ $customers->links() }}
    </div>
</div>
