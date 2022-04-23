<div>
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
    @if (session()->has('failures'))
    <div class="alert alert-success">
        <p>Vendors partially imported.</p>
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
    <div class="float-end mb-4">
      <a href="{{route('vendors.create')}}" type="button"  class="btn btn-primary rounded-pill"><i class="bi bi-plus"></i> Add Vendor</a>
      <button data-bs-toggle="modal" data-bs-target="#import" class="btn btn-primary rounded-pill"><i class="bi bi-arrow-down"></i> Import</button>
    </div>
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>
                    <input wire:model="selectPageRows" type="checkbox" value="" name="vendor_ids" id="vendor_ids">
                    <label for="vendor_ids"></label>
                </th>
                <th>Vendor</th>
                <th>Contact</th>
                <th>Phone</th>
                <th>Email</th>
                <th width="280px">Actions</th>
            </tr>
        </thead>
        @foreach ($vendors as $key => $vendor)
        <tr>
            <td>
                <input wire:model="selectedRows" type="checkbox" value="{{ $vendor->id }}" name="vendor_ids" id="{{ $vendor->id }}">
                <label for="{{ $vendor->id }}"></label>
            </td>
            <td>{{ $vendor->name }}</td>
            <td>{{ $vendor->contact_person }}</td>
            <td>{{ isset($vendor->contact) ? $vendor->contact->mobile : null }}</td>
            <td>{{ isset($vendor->presentAddress) ? $vendor->presentAddress->email : null }}</td>
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
                    <li><a class="dropdown-item"  href="{{ route('vendors.edit',$vendor->id) }}">Edit</a></li>
                    <li><a class="dropdown-item" href="#" wire:click="destroy({{ $vendor->id }})" >Delete</a></li>
                    </ul>
                </div>                             
            </td>
        </tr>
        @endforeach
    </table>
    <div class="pt-2">
        {{ $vendors->links() }}
    </div>
</div>
