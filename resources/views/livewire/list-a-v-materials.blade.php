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
                        <input wire:model="selectPageRows" type="checkbox" value="" name="av_material_ids" id="av_material_ids">
                        <label for="av_material_ids"></label>
                    </th>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Title</th>
                    <th>Pub Yr</th>
                    <th>QTY</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Total Amount</th>
                    <th>Subject</th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach ($avMaterials as $key => $avMaterial)
            <tr>
                <td>
                    <input wire:model="selectedRows" type="checkbox" value="{{ $avMaterial->id }}" name="av_material_ids" id="{{ $avMaterial->id }}">
                    <label for="{{ $avMaterial->id }}"></label>
                </td>
                <td>{{ ($avMaterials ->currentpage()-1) * $avMaterials ->perpage() + $key + 1 }}</td>
                <td>{{ $avMaterial->item_code }}</td>
                <td>{{ $avMaterial->author->name }}</td>
                <td>{{ $avMaterial->publisher->name }}</td>
                <td>{{ $avMaterial->product->title }}</td>
                <td>{{ $avMaterial->publication_year }}</td>
                <td>{{ $avMaterial->product->quantity }}</td>
                <td>{{ $avMaterial->product->price }}</td>
                <td>{{ $avMaterial->discount }}</td>
                <td>{{ $avMaterial->total_amount }}</td>
                <td>{{ $avMaterial->product->subject }}</td>
                <td>
                <div class="btn-group">
                    <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('print.av-materials.show',$avMaterial->id) }}">Show</a></li>
                        <li><a class="dropdown-item"  href="{{ route('print.av-materials.edit',$avMaterial->id) }}">Edit</a></li>
                        <li><a class="dropdown-item" href="#" wire:click="destroy({{ $avMaterial->id }})" >Delete</a></li>
                    </ul>
                </div>    
                </td>
            </tr>
            @endforeach
        </table>
        <div class="p-4">
            {!! $avMaterials->render() !!}
        </div>
    </div>
</div>
