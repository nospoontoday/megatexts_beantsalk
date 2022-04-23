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
    
    <form wire:submit.prevent="update({{$quotationId}})">
        @csrf
    <div wire:key="quotation">
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Project Title</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="project_title" 
                />
            </div>
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">PR Number</label>
                <input 
                    type="number"
                    class="form-control bg-white"
                    wire:model="pr_number" 
                />
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Deadline</label>
                <input 
                    type="date"
                    class="form-control bg-white"
                    wire:model="deadline" 
                />
            </div>
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Bidding Date</label>
                <input 
                    type="date"
                    class="form-control bg-white"
                    wire:model="bidding_date" 
                />

            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Terms and Conditions</label>
                <textarea wire:model="terms_conditions" name="" class="form-control bg-white" cols="30" rows="10"></textarea>
            </div>
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Purpose</label>

                <select
                    class="form-control bg-white"
                    wire:model="purpose_id"
                    wire:key="purpose_id">
                    <option value="">-- choose purpose --</option>
                    @foreach ($purposes as $key => $purpose)
                        <option
                            class="form-control bg-white"
                            value="{{ $purpose->id }}">{{ $purpose->name }}</option>
                    @endforeach
                </select>
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
                    <th>Discount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($productQuotations as $index => $product)
                        <tr wire:key="products-group-{{$index}}">
                            <td>
                                <select 
                                    name="productQuotations[{{$index}}].type" 
                                    class="form-control"
                                    wire:key="productQuotations.{{$index}}.type"
                                    wire:model="productQuotations.{{$index}}.type"
                                    wire:change="updateProductList({{$index}})"
                                >
                                    <option value="print-books">Print Books</option>
                                    <option value="print-journals">Print Journals</option>
                                </select>
                            </td>
                            <td>
                                <input
                                    list="productQuotations-{{$index}}-title" 
                                    class="form-control"
                                    wire:model="productQuotations.{{$index}}.title"
                                    wire:key="productQuotations.{{$index}}.title"
                                >                  
                            </td>
                            <td>
                                <input
                                    min="0.01"
                                    step="any"
                                    type="number"
                                    name="productQuotations[{{$index}}][price]"
                                    class="form-control"
                                    wire:key="productQuotations.{{$index}}.price"
                                    wire:model="productQuotations.{{$index}}.price"
                                />
                            </td>
                            <td>
                                <input
                                    min="1"
                                    type="number"
                                    name="productQuotations[{{$index}}][quantity]"
                                    class="form-control"
                                    wire:key="productQuotations.{{$index}}.quantity"
                                    wire:model="productQuotations.{{$index}}.quantity" 
                                />
                                <input 
                                    type="hidden"
                                    name="productQuotations[{{$index}}][product_id]"
                                    wire:key="productQuotations.{{$index}}.product_id"
                                    wire:model="productQuotations.{{$index}}.product_id"
                                />
                            </td>
                            <td>
                                <input
                                    min="0"
                                    type="number"
                                    name="productQuotations[{{$index}}][discount]"
                                    class="form-control"
                                    wire:key="productQuotations.{{$index}}.discount"
                                    wire:model="productQuotations.{{$index}}.discount" 
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
          <a type="button" href="{{url('customers')}}" class="btn bg-light rounded-pill mx-4 px-4"> Cancel</a>
          <button type="submit"  class="btn btn-primary rounded-pill px-4"> Save</button>
    </div>
    </form>
</div>