<div>    
    <div class="btn-group float-end">
        <button 
            type="button" 
            class="btn dropdown-toggle btn-primary rounded-pill" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">

            Action
        
        </button>
        <ul class="dropdown-menu">
            <li><a wire:click.prevent="updateStatus('approved')" class="dropdown-item" href="#">Approved</a></li>
            <li><a wire:click.prevent="updateStatus('cancelled')" class="dropdown-item"  href="#">Cancelled</a></li>
            <li><a wire:click.prevent="updateStatus('pending')" class="dropdown-item" href="#" >Pending</a></li>
        </ul>
    </div>       
    <div class="pull-left mb-4">
        {!! $status !!}
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <h3 class="fw-bolder"> {{ $project_title }}</h3>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>PR Number:</strong>
        {{ $pr_number }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Deadline:</strong>
        {{ $deadline }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Bidding Date:</strong>
        {{ $bidding_date }}
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 mb-2">
    <div class="form-group">
        <strong>Purpose:</strong>
        {{ $purpose_name }}
    </div>
</div>
@if($showPrintBooks)
<livewire:show-print-books :products="$products" />
@endif
@if($showPrintJournals)
<livewire:show-print-journals :products="$products" />
@endif
