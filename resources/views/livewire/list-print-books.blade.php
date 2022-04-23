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
                <input wire:model="selectPageRows" type="checkbox" value="" name="print_book_ids" id="print_book_ids">
                <label for="print_book_ids"></label>
              </th>
              <th>S/N</th>
              <th>ISBN-13</th>
              <th>Author</th>
              <th>Publisher</th>
              <th>Title/ED</th>
              <th>Pub Yr</th>
              <th>QTY</th>
              <th>Unit Price</th>
              <th>Discount</th>
              <th>Total Amount</th>
              <th>Subject</th>
              <th>Action</th>
            </tr>
          </thead>
          @foreach ($printBooks as $key => $printBook)
          <tr>
            <td>
              <input wire:model="selectedRows" type="checkbox" value="{{ $printBook->id }}" name="print_book_ids" id="{{ $printBook->id }}">
              <label for="{{ $printBook->id }}"></label>
            </td>
            <td>{{ ($printBooks ->currentpage()-1) * $printBooks ->perpage() + $key + 1 }}</td>
            <td>{{ $printBook->isbn_13 }}</td>
            <td>{{ $printBook->author->name ?? null }}</td>
            <td>{{ $printBook->publisher->name ?? null }}</td>
            <td>{{ $printBook->product->title }}</td>
            <td>{{ $printBook->publication_year }}</td>
            <td>{{ $printBook->product->quantity ?? null }}</td>
            <td>{{ $printBook->product->price ?? null }}</td>
            <td>{{ $printBook->discount }}</td>
            <td>{{ $printBook->total_amount }}</td>
            <td>{{ $printBook->product->subject ?? null }}</td>
            <td>
              <div class="btn-group">
                <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  Action
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('print.print-books.show',$printBook->id) }}">Show</a></li>
                  <li><a class="dropdown-item"  href="{{ route('print.print-books.edit',$printBook->id) }}">Edit</a></li>
                  <li><a class="dropdown-item" href="#" wire:click="destroy({{ $printBook->id }})" >Delete</a></li>
                </ul>
              </div>    
            </td>
          </tr>
         @endforeach
          </table>
          <div class="pt-2">
            {{ $printBooks->links() }}
          </div>
      </div>
</div>
