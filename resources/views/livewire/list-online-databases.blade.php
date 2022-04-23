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
                <input wire:model="selectPageRows" type="checkbox" value="" name="online_database_ids" id="online_database_ids">
                <label for="online_database_ids"></label>
              </th>
              <th>No</th>
              <th>Publisher</th>
              <th>Platform</th>
              <th>Access Model</th>
              <th>Title</th>
              <th>Subscription Period</th>
              <th>Unit Price</th>
              <th>QTY</th>
              <th>Total Amount</th>
              <th>Subject</th>
              <th>Action</th>
            </tr>
          </thead>
          @foreach ($onlineDatabases as $key => $onlineDatabase)
          <tr>
            <td>
              <input wire:model="selectedRows" type="checkbox" value="{{ $onlineDatabase->id }}" name="online_database_ids" id="{{ $onlineDatabase->id }}">
              <label for="{{ $onlineDatabase->id }}"></label>
            </td>
            <td>{{ ($onlineDatabases ->currentpage()-1) * $onlineDatabases ->perpage() + $key + 1 }}</td>
            <td>{{ $onlineDatabase->publisher->name }}</td>
            <td>{{ $onlineDatabase->platform->name }}</td>
            <td>{{ $onlineDatabase->accessModel->name }}</td>
            <td>{{ $onlineDatabase->product->title }}</td>
            <td>{{ $onlineDatabase->subscription_period }}</td>
            <td>{{ $onlineDatabase->product->price }}</td>
            <td>{{ $onlineDatabase->product->quantity }}</td>
            <td>{{ $onlineDatabase->total_amount }}</td>
            <td>{{ $onlineDatabase->product->subject }}</td>
            <td>
              <div class="btn-group">
                <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  Action
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('digital.online-databases.show',$onlineDatabase->id) }}">Show</a></li>
                  <li><a class="dropdown-item"  href="{{ route('digital.online-databases.edit',$onlineDatabase->id) }}">Edit</a></li>
                  <li><a class="dropdown-item" href="#" wire:click="destroy({{ $onlineDatabase->id }})">Delete</a></li>
                </ul>
              </div>    
            </td>
          </tr>
        @endforeach
          </table>
          <div class="p-4">
            {{ $onlineDatabases->links() }}
          </div>
      </div>
</div>
