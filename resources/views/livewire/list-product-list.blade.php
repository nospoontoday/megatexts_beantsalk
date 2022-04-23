<div>
    <div class="container px-0">
        <div class="row ">
            <div class="float-start mb-4 col-md-3">
                <select wire:model="selectedType" wire:change="updateProductList()" name="" id="" class="form-select bg-white rounded-pill">
                    @foreach($types as $type)
                    @if($type->category_id == 1)
                    
                    <option value="{{$type->id}}">{{ ucwords(str_replace('-', ' ', $type->name)) }}</option>
                    @endif
                    @endforeach
                </select>        
            </div>
            <div class="float-start mb-4 col-md-3">
                <select wire:model="selectedType" wire:change="updateProductList()" name="" id="" class="form-select bg-white rounded-pill">
                    <option selected value="digital">Digital</option>
                    @foreach($types as $type)
                    @if($type->category_id == 2)
                    
                    <option value="{{$type->id}}">{{ ucwords(str_replace('-', ' ', $type->name)) }}</option>
                    @endif
                    @endforeach
                </select>        
            </div>
        </div>
    </div>
    <div class="float-start mb-4 me-3">
        <input 

            type="text" 

            class="form-control rounded-pill bg-white"

            wire:model="term"
        />
    </div>
    <div class="float-start mb-4">
        <button type="button"  class="btn btn-info rounded-pill"> Filter</button>
    </div>
    @if (session()->has('failures'))   
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

    @if($selectedType == 1)
        <livewire:list-print-books-product-list :products="$products" />

    @elseif($selectedType == 2)
        <livewire:list-print-journals-product-list :products="$products" />

    @elseif($selectedType == 3)
        <livewire:list-av-materials-product-list :products="$products" />

    @elseif($selectedType == 4)
        <livewire:list-library-fixtures-product-list :products="$products" />
    @endif
</div>
