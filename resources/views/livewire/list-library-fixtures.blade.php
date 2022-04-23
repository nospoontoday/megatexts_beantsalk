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
                        <input wire:model="selectPageRows" type="checkbox" value="" name="library_fixture_ids" id="library_fixture_ids">
                        <label for="library_fixture_ids"></label>
                    </th>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Manufacturer</th>
                    <th>Description</th>
                    <th>Dimension</th>
                    <th>Unit Price</th>
                    <th>QTY</th>
                    <th>Total Amount</th>
                    <th>Vatable Sales</th>
                    <th>VAT</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach ($libraryFixtures as $key => $libraryFixture)
            <tr>
                <td>
                    <input wire:model="selectedRows" type="checkbox" value="{{ $libraryFixture->id }}" name="library_fixture_ids" id="{{ $libraryFixture->id }}">
                    <label for="{{ $libraryFixture->id }}"></label>
                </td>
                <td>{{ ($libraryFixtures ->currentpage()-1) * $libraryFixtures ->perpage() + $key + 1 }}</td>
                <td>{{ $libraryFixture->item_code }}</td>
                <td>{{ $libraryFixture->manufacturer->name }}</td>
                <td>{{ $libraryFixture->product->title }}</td>
                <td>{{ $libraryFixture->dimension }}</td>
                <td>{{ $libraryFixture->product->price }}</td>
                <td>{{ $libraryFixture->product->quantity }}</td>
                <td>{{ $libraryFixture->total_amount }}</td>
                <td>{{ $libraryFixture->vatable_sales }}</td>
                <td>{{ $libraryFixture->vat }}</td>
                <td>{{ $libraryFixture->product->subject }}</td>
                <td>
                <div class="btn-group">
                    <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('print.library-fixtures.show',$libraryFixture->id) }}">Show</a></li>
                        <li><a class="dropdown-item"  href="{{ route('print.library-fixtures.edit',$libraryFixture->id) }}">Edit</a></li>
                        <li><a class="dropdown-item" href="#" wire:click="destroy({{ $libraryFixture->id }})" >Delete</a></li>
                    </ul>
                </div>    
                </td>
            </tr>
            @endforeach
        </table>
        <div class="pt-2">
            {!! $libraryFixtures->render() !!}
        </div>
    </div>
</div>
