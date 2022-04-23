<div>    
    <div class="btn-group float-end">
        @if($showSendButton)
        <button 
            type="button" 
            class="btn btn-primary rounded-pill" 
            aria-expanded="false"
            data-bs-toggle="modal" 
            data-bs-target="#sending"
        >
            Sending Packing
        </button>
        @endif
        <button 
            type="button" 
            class="btn dropdown-toggle btn-primary rounded-pill" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">

            Action
        
        </button>
        <ul class="dropdown-menu">
            <li><a wire:click.prevent="updateStatus('topack')" class="dropdown-item" href="#">Approve</a></li>
            <li><a wire:click.prevent="updateStatus('cancel')" class="dropdown-item"  href="#">Cancel</a></li>
            <li><a wire:click.prevent="updateStatus('forPO')" class="dropdown-item" href="#" >For PO</a></li>
            <li><a class="dropdown-item" href="#">Export</a></li>
            <li><a class="dropdown-item" href="#">Edit</a></li>
        </ul>
    </div>       
    <div class="pull-left mb-4">
        {!! $status !!}
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <h3 class="fw-bolder"> {{ $companyName }}</h3>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Address:</strong>
        {{ $address }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>City:</strong>
        {{ $city }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>State:</strong>
        {{ $state }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Email:</strong>
        {{ $email }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Contact Number:</strong>
        {{ $contactNumber }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Buyer Name:</strong>
        {{ $buyerName }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <h3 class="fw-bolder"> {{ $branchName }}</h3>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Date:</strong>
        {{ $date }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>IORF Number:</strong>
        {{ $iorfNumber }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>PO/PR Number:</strong>
        {{ $poNumber }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Amount:</strong>
        PHP {{ $total }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Sales Rep:</strong>
        {{ $salesRep }}
    </div>
</div>
@if($showPrintBooks)
<livewire:show-print-books :products="$products" />
@endif
@if($showPrintJournals)
<livewire:show-print-journals :products="$products" />
@endif
@if($showAvMaterials)
<livewire:show-av-materials :products="$products" />
@endif
@if($showLibraryFixtures)
<livewire:show-library-fixtures :products="$products" />
@endif
@if($showEBooks)
<livewire:show-e-books :products="$products" />
@endif
@if($showEJournals)
<livewire:show-e-journals :products="$products" />
@endif
@if($showOnlineDatabases)
<livewire:show-online-databases :products="$products" />
@endif
@if($showLibraryTechnologies)
<livewire:show-library-technologies :products="$products" />
@endif

<!-- Modal -->
<div class="modal fade" id="sending" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-body">
                <div class="px-4">
                    <i type="submit" class="bi bi-x-circle float-end" data-bs-dismiss="modal" aria-label="Close"></i>
                </div>
                <div class="px-4 pt-4">
                    <b class="fs-3">CONFIRMATION</b>
                </div> 
                <form method="POST" action="{{ route('shipments.store') }}">
                    @csrf
                    <input type="hidden" name="salesOrderId" value="{{$salesOrder->id}}">
                    <div class="mb-4 row">
                        <label class="form-label mb-0 fw-bold" for="ship_via">Ship Via:</label>
                        <input 
                            type="text" 
                            class="form-control bg-white" 
                            placeholder="YFE Worldwide Logistics"
                            name="shipVia"
                        />
                    </div>
                    <div class="mb-4 row">
                        <label class="form-label mb-0 fw-bold" for="shipping_address">Shipping Address:</label>
                        <input 
                            type="text" 
                            class="form-control bg-white" 
                            name="shippingAddress"
                        />
                    </div>
                    <div class="mb-4 row">
                        <label class="form-label mb-0 fw-bold" for="pickup_address">Pick Up Address:</label>
                        <input 
                            type="text" 
                            class="form-control bg-white" 
                            name="pickupAddress"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary text-center rounded-pill">OK</button>
                </form>
            </div>
        </div>
    </div>
</div>