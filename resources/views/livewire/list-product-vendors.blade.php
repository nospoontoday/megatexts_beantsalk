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
    
    <form wire:submit.prevent="submit">
        @csrf
    <div class="mb-2 row">
        <div class="col-4">
            <label class="form-label mb-0 fw-bold">Company Name</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.company_name" 
            />
        </div>
        <div class="col-4">
            <label class="form-label mb-0 fw-bold">Contact Person</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.contact_person" 
            />
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-4">
            <label class="form-label mb-0 fw-bold">Phone Number</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.phone_number" 
            />
        </div>
        <div class="col-4">
            <label class="form-label mb-0 fw-bold">Email</label>
            <input 
                type="email"
                class="form-control bg-white"
                wire:model="vendors.email" 
            />
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-4">
            <label class="form-label mb-0 fw-bold">Website</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.website" 
            />
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-8">
            <label class="form-label mb-0 fw-bold">Address</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.address" 
            />
        </div>
    </div>
    <div class="mb-2 row">
        <div class="col-2">
            <label class="form-label mb-0 fw-bold">City</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.city" 
            />
        </div>
        <div class="col-2">
            <label class="form-label mb-0 fw-bold">State</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.state" 
            />
        </div>
        <div class="col-2">
            <label class="form-label mb-0 fw-bold">Zip</label>
            <input 
                type="text"
                class="form-control bg-white"
                wire:model="vendors.zip" 
            />
        </div>
    </div>
    <div class="card" wire:key="products">
        <div class="mt-3 pb-0">
            <h4 class="fw-bold">PRODUCTS</h4>
        </div>

        <div class="card-body px-0">
            <table class="table text-center" id="products_table">
                <thead class="bg-info">
                <tr>
                    <th>Type</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                    @foreach ($productVendors as $index => $productVendor)
                        <tr wire:key="products-group-{{$index}}">
                            <td>
                                <select 
                                    name="productVendors[{{$index}}].type" 
                                    class="form-control bg-white"
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
                                    class="form-control bg-white"
                                    wire:model.defer="productVendors.{{$index}}.title"
                                    wire:key="productVendors.{{$index}}.title"
                                >
                                <datalist id="productVendors-{{$index}}-title">
                                    <option value="">-- choose product --</option>

                                    @foreach ($books as $book)
                                    <option 
                                        value="{{ $book->product->title }}">
                                    </option>
                                    @endforeach
                                </datalist>                             
                            </td>
                            <td>
                                <input
                                    min="0.01"
                                    step="any"
                                    type="number"
                                    name="productVendors[{{$index}}][price]"
                                    class="form-control bg-white"
                                    wire:key="productVendors.{{$index}}.price"
                                    wire:model="productVendors.{{$index}}.price"
                                />
                            </td>
                            <td>
                                <input
                                    min="1"
                                    type="number"
                                    name="productVendors[{{$index}}][quantity]"
                                    class="form-control bg-white"
                                    wire:key="productVendors.{{$index}}.quantity"
                                    wire:model="productVendors.{{$index}}.quantity" 
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
                    <button class="btn btn-primary rounded-pill"
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