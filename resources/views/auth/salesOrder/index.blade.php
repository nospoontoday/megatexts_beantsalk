@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">              
                <div class="tab {{ ! app('request')->input('status') ? 'active' : '' }}"><a class="{{ ! app('request')->input('status') ? 'fw-bold text-primary' : '' }}" href="{{url('sales-order')}}">Print Book SO</a></div>
                <div class="tab {{ app('request')->input('status') && app('request')->input('status') == 'approved' ? 'active' : '' }}"><a class="{{ app('request')->input('status') && app('request')->input('status') == 'approved' ? 'fw-bold text-primary' : '' }}" href="{{url('sales-order?status=approved')}}"">Approved Orders</a></div>
                <div class="tab {{ app('request')->input('status') && app('request')->input('status') == 'cancelled' ? 'active' : '' }}"><a class="{{ app('request')->input('status') && app('request')->input('status') == 'cancelled' ? 'fw-bold text-primary' : '' }}" href="{{url('sales-order?status=cancelled')}}"">Cancelled Orders</a></div>
            </div>
            <div class="card pt-0">
                <livewire:list-sales-orders />
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="salesOrder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 px-0">
      <div class="modal-body px-0">
        <div class="px-4">
          <i type="submit" class="bi bi-x-circle float-end" data-bs-dismiss="modal" aria-label="Close"></i>
        </div>
        <div class="px-4 py-4">
          <b class="fs-3">ADD SALES ORDER</b>
        </div> 
        <div class="container px-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content fw-bolder">
                            PRINT
                        </div>
                    </div>
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                            <a href="{{route('create-print-books-sales-order')}}">Add Print Books</a>
                        </div>
                    </div>
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                        <a href="{{route('create-print-journals-sales-order')}}">Add Print Journals</a>
                        </div>
                    </div>
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                        <a href="{{route('create-av-materials-sales-order')}}">Add AV Materials</a>
                        </div>
                    </div>
                    <div class="mb-4 px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                        <a href="{{route('create-library-fixtures-sales-order')}}">Add Library Fixtures</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content fw-bolder">
                            DIGITAL
                        </div>
                    </div>
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                            <a href="{{route('create-e-books-sales-order')}}">Add E-Books</a>
                        </div>
                    </div>
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                        <a href="{{route('create-e-journals-sales-order')}}">Add E-Journals</a>
                        </div>
                    </div>
                    <div class="px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                        <a href="{{route('create-online-databases-sales-order')}}">Add Online Databases</a>
                        </div>
                    </div>
                    <div class="mb-4 px-0 list-group-item list-group-item-action border-0 text-center">
                        <div class="highlight-content">
                        <a href="{{route('create-library-technologies-sales-order')}}">Add Library Technologies</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection