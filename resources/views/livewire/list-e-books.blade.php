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
                    <input wire:model="selectPageRows" type="checkbox" value="" name="e_book_ids" id="e_book_ids">
                    <label for="e_book_ids"></label>
                </th>
                <th>No</th>
                <th>E-ISBN</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Platform</th>
                <th>Access Model</th>
                <th>Title</th>
                <th>Pub Yr</th>
                <th>QTY</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Subject</th>
                <th>Action</th>
            </tr>
            </thead>
            @foreach ($eBooks as $key => $eBook)
            <tr>
                <td>
                    <input wire:model="selectedRows" type="checkbox" value="{{ $eBook->id }}" name="e_book_ids" id="{{ $eBook->id }}">
                    <label for="{{ $eBook->id }}"></label>
                </td>
                <td>{{ ($eBooks ->currentpage()-1) * $eBooks ->perpage() + $key + 1 }}</td>
                <td>{{ $eBook->e_isbn }}</td>
                <td>{{ $eBook->author->name }}</td>
                <td>{{ $eBook->publisher->name }}</td>
                <td>{{ $eBook->platform->name }}</td>
                <td>{{ $eBook->accessModel->name }}</td>
                <td>{{ $eBook->product->title }}</td>
                <td>{{ $eBook->publication_year }}</td>
                <td>{{ $eBook->product->quantity }}</td>
                <td>{{ $eBook->product->price }}</td>
                <td>{{ $eBook->total_amount }}</td>
                <td>{{ $eBook->product->subject }}</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                        </button>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('digital.e-books.show',$eBook->id) }}">Show</a></li>
                        <li><a class="dropdown-item"  href="{{ route('digital.e-books.edit',$eBook->id) }}">Edit</a></li>
                        <li><a class="dropdown-item" href="#" wire:click="destroy({{ $eBook->id }})" >Delete</a></li>
                        </ul>
                    </div>    
                </td>
            </tr>
            @endforeach
        </table>
        <div class="pt-4">
            {{ $eBooks->links() }}
        </div>
    </div>
</div>
