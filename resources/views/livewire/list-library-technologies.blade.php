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
          <button type="button"  class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addproduct"><i class="bi bi-plus"></i> Add Product</button>
          <button data-bs-toggle="modal" data-bs-target="#import" class="btn btn-primary rounded-pill"><i class="bi bi-arrow-down"></i> Import</button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table text-center">
          <thead class="bg-info ">
            <tr>
              <th>
                <input wire:model="selectPageRows" type="checkbox" value="" name="library_technology_ids" id="library_technology_ids">
                <label for="library_technology_ids"></label>
              </th>
              <th>No</th>
              <th>Item Code</th>
              <th>Developer</th>
              <th>Title</th>
              <th>Subscription Period</th>
              <th>Unit Price</th>
              <th>QTY</th>
              <th>Total Amount</th>
              <th>Vatable Sales</th>
              <th>VAT</th>
              <th>Subject</th>
              <th>Action</th>
            </tr>
          </thead>
          @foreach ($libraryTechnologies as $key => $libraryTechnology)
          <tr>
            <td>
              <input wire:model="selectedRows" type="checkbox" value="{{ $libraryTechnology->id }}" name="library_technology_ids" id="{{ $libraryTechnology->id }}">
              <label for="{{ $libraryTechnology->id }}"></label>
            </td>
            <td>{{ ($libraryTechnologies ->currentpage()-1) * $libraryTechnologies ->perpage() + $key + 1 }}</td>
            <td>{{ $libraryTechnology->item_code }}</td>
            <td>{{ $libraryTechnology->developer->name }}</td>
            <td>{{ $libraryTechnology->product->title }}</td>
            <td>{{ $libraryTechnology->subscription_period }}</td>
            <td>{{ $libraryTechnology->product->price }}</td>
            <td>{{ $libraryTechnology->product->quantity }}</td>
            <td>{{ $libraryTechnology->total_amount }}</td>
            <td>{{ $libraryTechnology->vatable_sales }}</td>
            <td>{{ $libraryTechnology->vat }}</td>
            <td>{{ $libraryTechnology->product->subject }}</td>
            <td>
              <div class="btn-group">
                <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  Action
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('digital.library-technologies.show',$libraryTechnology->id) }}">Show</a></li>
                  <li><a class="dropdown-item"  href="{{ route('digital.library-technologies.edit',$libraryTechnology->id) }}">Edit</a></li>
                  <li><a class="dropdown-item" href="#" wire:click="destroy({{ $libraryTechnology->id }})">Delete</a></li>
                </ul>
              </div>    
            </td>
          </tr>
        @endforeach
          </table>
          <div class="p-4">
            {{ $libraryTechnologies->links() }}
          </div>
      </div>
</div>
