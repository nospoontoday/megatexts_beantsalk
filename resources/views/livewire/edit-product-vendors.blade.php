<div class="card-body p-4">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    
    <form wire:submit.prevent="update({{$vendorId}})">
        @csrf
    <div wire:key="vendor">
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Vendor</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="name" 
                />
            </div>
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Contact</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="contact_person" 
                />
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Phone</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="phone_number" 
                />
            </div>
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Email</label>
                <input 
                    type="email"
                    class="form-control bg-white"
                    wire:model="email" 
                />
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Website</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="website" 
                />
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-8">
                <label class="form-label mb-0 fw-bold">Address</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="address" 
                />
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-2">
                <label class="form-label mb-0 fw-bold">City</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="city" 
                />
            </div>
            <div class="col-2">
                <label class="form-label mb-0 fw-bold">State</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="state" 
                />
            </div>
            <div class="col-2">
                <label class="form-label mb-0 fw-bold">Zip</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="zip" 
                />
            </div>
        </div>
    </div>
    <div class="card" wire:key="products">
        <div class="card-header">
            Products
        </div>

        <div class="card-body">
            <table class="table" id="products_table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($productVendors as $index => $product)
                        <tr wire:key="products-group-{{$index}}">
                            <td>
                                <select 
                                    name="productVendors[{{$index}}].type" 
                                    class="form-control"
                                    wire:key="productVendors.{{$index}}.type"
                                    wire:model="productVendors.{{$index}}.type"
                                    wire:change="updateProductList({{$index}})"
                                >
                                    <option value="print-books">Print Books</option>
                                    <option value="print-journals">Print Journals</option>
                                </select>
                            </td>
                            <td>
                                <input
                                    list="productVendors-{{$index}}-title" 
                                    class="form-control"
                                    wire:model="productVendors.{{$index}}.title"
                                    wire:key="productVendors.{{$index}}.title"
                                >                  
                            </td>
                            <td>
                                <input
                                    min="0.01"
                                    step="any"
                                    type="number"
                                    name="productVendors[{{$index}}][price]"
                                    class="form-control"
                                    wire:key="productVendors.{{$index}}.price"
                                    wire:model="productVendors.{{$index}}.price"
                                />
                            </td>
                            <td>
                                <input
                                    min="1"
                                    type="number"
                                    name="productVendors[{{$index}}][quantity]"
                                    class="form-control"
                                    wire:key="productVendors.{{$index}}.quantity"
                                    wire:model="productVendors.{{$index}}.quantity" 
                                />
                                <input 
                                    type="hidden"
                                    name="productVendors[{{$index}}][product_id]"
                                    wire:key="productVendors.{{$index}}.product_id"
                                    wire:model="productVendors.{{$index}}.product_id"
                                />
                            </td>
                            <td>
                                <a href="#" wire:click.prevent="removeProduct({{$index}})">Delete</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-secondary"
                        wire:click.prevent="addProduct">+ Add Another Product</button>
                </div>
            </div>
        </div>
    </div>
    <div class="float-end mt-4">
          <a type="button" href="{{url('vendors')}}" class="btn bg-light rounded-pill mx-4 px-4"> Cancel</a>
          <button type="submit"  class="btn btn-primary rounded-pill px-4"> Save</button>
    </div>
    </form>
</div>