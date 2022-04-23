@extends('layouts.sidebar')


@section('content')
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
<div class="card-body p-4">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
      <p>{{ $message }}</p>
    </div>
    @endif
</div>
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Details</a></div>
              </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                    <livewire:show-sales-order :salesOrder="$salesOrder" />
                </div>
            </div>
        </div>
    </div>
</div>

@endsection