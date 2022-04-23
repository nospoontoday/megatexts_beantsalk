@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab"><a href="{{url('print/print-books')}}">Print Books</a></div>
                <div class="tab"><a href="{{url('print/print-journals')}}">Print Journals</a></div>
                <div class="tab"><a href="{{url('print/av-materials')}}">AV Materials</a></div>
                <div class="tab active"><a class="fw-bold text-primary" href="#">Library Fixtures</a></div>
              </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                  <livewire:list-library-fixtures />
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="addproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 px-0">
      <div class="modal-body px-0">
        <div class="px-4">
          <i type="submit" class="bi bi-x-circle float-end" data-bs-dismiss="modal" aria-label="Close"></i>
        </div>
        <div class="px-4 py-4">
          <b class="fs-3">ADD PRODUCT</b>
        </div> 
        <div class="px-0 list-group-item list-group-item-action border-0 text-center">
            <div class="highlight-content">
                <a href="{{ route('print.print-books.create') }}">Add Print Books</a>
            </div>
        </div>
        <div class="px-0 list-group-item list-group-item-action border-0 text-center">
            <div class="highlight-content">
              <a href="{{ route('print.print-journals.create') }}">Add Print Journals</a>
            </div>
        </div>
        <div class="px-0 list-group-item list-group-item-action border-0 text-center">
            <div class="highlight-content">
              <a href="{{ route('print.av-materials.create') }}">Add AV Materials</a>
            </div>
        </div>
        <div class="mb-4 px-0 list-group-item list-group-item-action border-0 text-center">
            <div class="highlight-content">
              <a href="{{ route('print.library-fixtures.create') }}">Add Library Fixtures</a>
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
            {!! Form::open(array('route' => 'print.library-fixture-import','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
            {!! Form::file('library_fixtures_import', null,array('class' => 'form-control bg-white')) !!}
                <button type="submit" class="btn btn-primary rounded-pill"><i class="bi bi-down"></i> Upload</button>
            {!! Form::close() !!}
            </div>
      </div>
    </div>
  </div>
</div>
@endsection