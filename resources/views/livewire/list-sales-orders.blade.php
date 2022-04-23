<div class="card-body p-4">
    <div class="mb-4">
       <!-- List Livewire -->
       <div class="container px-0">
              <div class="row ">
                  <div class="float-start mb-4 col-md-3">
                      <select wire:model="type" name="" id="" class="form-select bg-white rounded-pill">
                          <option selected value="print-books">Print Books</option>
                          <option value="print-journals">Print Journals</option>
                          <option value="AV-materials">AV Materials</option>
                          <option value="library-fixtures">Library Fixtures</option>
                      </select>        
                  </div>
                  <div class="float-start mb-4 col-md-3">
                      <select wire:model="type" name="" id="" class="form-select bg-white rounded-pill">
                          <option selected>Digital</option>
                          <option value="e-books">EBooks</option>
                          <option value="e-journals">EJournals</option>
                          <option value="online-databases">Online Databases</option>
                          <option value="library-technologies">Library Technologies</option>
                      </select>        
                  </div>
              </div>
          </div>
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
            <div class="float-end mb-4">
                <button data-bs-toggle="modal" data-bs-target="#salesOrder" class="btn btn-primary rounded-pill"><i class="bi bi-plus"></i> Add Sales Orders</button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table text-center">
                <thead class="bg-info ">
                    <tr>
                        <th></th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Order Summary</th>
                        <th>Status</th>
                        <th>Amount Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                @foreach($salesOrders as $key => $salesOrder)
                <tr>
                    <td>
                        <input wire:model="selectedRows" type="checkbox" value="" name="" id="">
                        <label for=""></label>
                    </td>
                    <td>{{ $salesOrder->customer->company_name ?? null  }}</td>
                    <td>{{ $salesOrder->customer->presentAddress->present_address ?? null }}</td>
                    <td>{{ $salesOrder->customer->presentAddress->email ?? null }}</td>
                    <td>{{ $salesOrder->customer->contact->mobile ?? null }}</td>
                    <td>{{ $salesOrder->order_summary }}</td>
                    <td>
                        @if($salesOrder->status == "pending")
                            <div class="p-0 alert alert-warning fw-bold">Pending</div>
                        @elseif($salesOrder->status == "served")
                            <div class="p-0 alert alert-success fw-bold">Served</div>
                        @elseif($salesOrder->status == "delivered")
                            <div class="p-0 alert alert-info fw-bold">Delivered</div>
                        @elseif($salesOrder->status == "packed")
                            <div class="p-0 alert alert-warning fw-bold">Packed</div>
                        @elseif($salesOrder->status == "topack")
                            <div class="p-0 alert alert-danger fw-bold">To Pack</div>
                        @else
                            <div class="p-0 alert alert-danger fw-bold">Cancelled</div>
                        @endif
                    </td>
                    <td>{{ $salesOrder->total_amount }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('sales-order.show', $salesOrder->id) }}" type="button" class="btn btn-secondary rounded-3 py-1 px-3 bg-primary text-white">View</a>
                            <button type="button" class="btn btn-secondary  px-2 dropdown-toggle dropdown-toggle-split bg-white text-primary border" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                <li><a class="dropdown-item"  href="">Edit</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                            </ul>
                        </div>       
                    </td>
                </tr>
                @endforeach()
            </table>
          </div>
          <div class="pt-2">
             {{ $salesOrders->links() }}
          </div>
       <!-- End List Livewire -->
    </div>
</div>