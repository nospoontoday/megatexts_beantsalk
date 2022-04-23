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
    <div class="col-md-12 offset-md-1">
        <div class="mb-2 row">
            <div class="col-md-4">
                <label class="form-label mb-0 fw-bold">Customer</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="customer"
                />
                <div class="position-absolute list-group bg-white w-auto rounded-1 shadow-lg">
                    @if($customer != null)
                    
                        @foreach($customers as $customer)
                            <a class="customer-link" wire:click="chooseCustomer({{ $customer->id }})" >
                                {{ $customer->company_name }}
                            </a>
                        @endforeach
                    
                    @endif
                </div>
            </div>
            <div class="col-md-4 offset-md-1">
                <label class="form-label mb-0 fw-bold">Branch</label>
                <select
                    class="form-control bg-white"
                    wire:model="branch"
                >

                @if(auth()->user()->is_admin)
                @foreach ($branches as $key => $branch)
                    <option
                        class="form-control bg-white"
                        value="{{ $branch->id }}">{{ $branch->name }}
                    </option>
                @endforeach
                @else
                @foreach ($branches as $key => $row)
                    @if($row->id == $branch)
                    <option
                        class="form-control bg-white"
                        value="{{ $row->id }}">{{ $row->name }}
                    </option>
                    @endif
                @endforeach
                @endif
                </select>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Address</label>
                <input 
                    type="text"
                    class="form-control"
                    wire:model="address"
                />
            </div>
            <div class="col-md-4 offset-md-1">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">Date</label>
                        <input 
                            type="date"
                            class="form-control bg-white"
                            wire:model="date"
                        />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">IORF No.</label>
                        <input 
                            type="text"
                            class="form-control"
                            wire:model="iorf"
                        />
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-2 row">
            <div class="col-4">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">City</label>
                        <input 
                            type="text"
                            class="form-control"
                            wire:model="city"
                        />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-0 fw-bold">State</label>
                        <input 
                            type="text"
                            class="form-control"
                            wire:model="state"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-4 offset-md-1">
                <label class="form-label mb-0 fw-bold">PO/PR Number</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="poNumber"
                />
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Email Address</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="email"
                />
            </div>
            <div class="col-md-4 offset-md-1">
                <label class="form-label mb-0 fw-bold">Sales Rep</label>
                <select
                    class="form-control bg-white"
                    wire:model="salesRep"
                >
                @if(auth()->user()->is_admin)
                    <option 
                        selected
                        class="form-control bg-white"
                        value="{{ auth()->user()->id }}"> {{ auth()->user()->full_name }}
                    </option>
                    @foreach ($salesReps as $key => $salesRep)
                        <option
                            class="form-control bg-white"
                            value="{{ $salesRep->id }}">{{ $salesRep->full_name }}
                        </option>
                    @endforeach
                @else
                <option 
                    selected
                    class="form-control bg-white"
                    value="{{ auth()->user()->id }}"> {{ auth()->user()->full_name }}
                </option>
                @endif
                </select>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Contact Number</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="contact"
                />
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-4">
                <label class="form-label mb-0 fw-bold">Buyer Name</label>
                <input 
                    type="text"
                    class="form-control bg-white"
                    wire:model="buyer"
                />
            </div>
        </div>
    </div>
    <div class="card" wire:key="products">
        <div class="mt-3 pb-0">
            <h4 class="fw-bold">PRODUCTS</h4>
        </div>

        <div class="card-body px-0">
            <div class="table-responsive">
                <table class="table text-center" id="products_table">
                    <thead class="bg-info">
                    <tr>
                        <th>S/N</th>
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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach ($productSalesOrders as $index => $productSalesOrder)
                            <tr wire:key="products-group-{{$index}}">
                                <td>
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    <input
                                        list="productSalesOrders-{{$index}}-e_isbn" 
                                        wire:model.defer="productSalesOrders.{{$index}}.e_isbn"
                                        wire:key="productSalesOrders.{{$index}}.e_isbn"
                                        wire:change="setProduct({{$index}}, $event.target.value)"
                                    >
                                    <datalist id="productSalesOrders-{{$index}}-e_isbn">
                                        <option value="">-- choose print book --</option>

                                        @foreach ($books as $book)
                                        <option 
                                            value="{{ $book->e_isbn }}">
                                        </option>
                                        @endforeach
                                    </datalist> 
                                </td>
                                <td>
                                    <input 
                                        type="text"
                                        name="author"
                                        wire:model.defer="productSalesOrders.{{$index}}.author"
                                        wire:key="productSalesOrders.{{$index}}.author"
                                    />
                                </td>
                                <td>
                                    <input 
                                        type="text"
                                        name="publisher"
                                        wire:model.defer="productSalesOrders.{{$index}}.publisher"
                                        wire:key="productSalesOrders.{{$index}}.publisher"
                                    />
                                </td>
                                <td>
                                    <input 
                                        type="text"
                                        name="platform"
                                        wire:model.defer="productSalesOrders.{{$index}}.platform"
                                        wire:key="productSalesOrders.{{$index}}.platform"
                                    />
                                </td>
                                <td>
                                    <input 
                                        type="text"
                                        name="access_model"
                                        wire:model.defer="productSalesOrders.{{$index}}.access_model"
                                        wire:key="productSalesOrders.{{$index}}.access_model"
                                    />
                                </td>
                                <td>
                                    <input 
                                        type="text"
                                        name="title"
                                        wire:model.defer="productSalesOrders.{{$index}}.title"
                                        wire:key="productSalesOrders.{{$index}}.title"
                                    />
                                </td>
                                <td>
                                    <input 
                                        type="text"
                                        name="publication_year"
                                        wire:model.defer="productSalesOrders.{{$index}}.publication_year"
                                        wire:key="productSalesOrders.{{$index}}.publication_year"
                                    />
                                </td>
                                <td>
                                    <input 
                                        min="1"
                                        type="number"
                                        name="quantity"
                                        wire:model.defer="productSalesOrders.{{$index}}.quantity"
                                        wire:key="productSalesOrders.{{$index}}.quantity"
                                        wire:change="setQuantity({{$index}}, $event.target.value)"
                                    />
                                </td>
                                <td>
                                    <input 
                                        min="0.01"
                                        step="any"
                                        type="number"
                                        name="price"
                                        wire:model.defer="productSalesOrders.{{$index}}.price"
                                        wire:key="productSalesOrders.{{$index}}.price"
                                        wire:change="setPrice({{$index}}, $event.target.value)"
                                    />
                                </td>
                                <td>
                                    <input
                                        min="0.0"
                                        step="any"
                                        type="number"
                                        name="total"
                                        readonly
                                        wire:model.defer="productSalesOrders.{{$index}}.total"
                                        wire:key="productSalesOrders.{{$index}}.total"
                                    />
                                </td>
                                <td>
                                    <a href="#" wire:click.prevent="removeProduct({{$index}})">Delete</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="row pt-4">
                <div class="col-md-12">
                    <button class="btn btn-primary rounded-pill"
                        wire:click.prevent="addProduct">+ Add Another Print Book</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 ms-auto">
                <table class="table">
                    <tr>
                        <td>Sub Total <div class="float-end" wire:model="subTotal">{{ number_format($subTotal, 2) }}</div></td>
                    </tr>
                    <tr>
                        <td>Discount <div class="float-end">{{ number_format($totalDiscount, 2) }}</div></td>  
                    </tr>
                    <tr>
                        <td class="bg-light">Total <div class="float-end">{{ number_format($total, 2) }}</div></td>  
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="float-end mt-4">
          <a type="button" href="{{url('sales-order')}}" class="btn bg-light rounded-pill mx-4 px-4"> Cancel</a>
          <button type="submit"  class="btn btn-primary rounded-pill px-4"> Save</button>
    </div>
</div>