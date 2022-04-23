@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">CUSTOMERS</a></div>
            </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                      <div class="mb-4">
                        <livewire:list-customers />
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="import" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-4 rounded-4">
      <div class="modal-body ">
      <div>
         <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
            <div class="mb-4">
              <b class="fs-3">Import</b>
            </div> 
            <div class="mb-4">
            {!! Form::open(array('route' => 'customer-import','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
            {!! Form::file('customers_import', null,array('class' => 'form-control bg-white')) !!}
                <button type="submit" class="btn btn-primary rounded-pill"><i class="bi bi-down"></i> Upload</button>
            {!! Form::close() !!}
            </div>
      </div>
    </div>
  </div>
</div>
@endsection