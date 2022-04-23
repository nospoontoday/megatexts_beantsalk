@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">CREATE NEW</a></div>
            </div>
            <div class="card pt-0">
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
                    
                    {!! Form::open(array('route' => 'customers.store','method'=>'POST')) !!}
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Company Name</label>
                            {!! Form::text('company_name', null,array('class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Buyer Name</label>
                            {!! Form::text('buyer_name', null,array('class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Phone Number</label>
                            {!! Form::text('mobile', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Email</label>
                            {!! Form::text('email', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Website</label>
                            <input type="text" class="form-control bg-white">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-8">
                            <label class="form-label mb-0 fw-bold">Address</label>
                            <input type="text" class="form-control bg-white">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-2">
                            <label class="form-label mb-0 fw-bold">City</label>
                            {!! Form::text('city', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-2">
                            <label class="form-label mb-0 fw-bold">State</label>
                            <input type="text" class="form-control bg-white">
                        </div>
                        <div class="col-2">
                            <label class="form-label mb-0 fw-bold">Zip</label>
                            <input type="text" class="form-control bg-white">
                        </div>
                    </div>
                    <div class="float-end mt-4">
                          <a type="button" href="{{url('customers')}}" class="btn bg-light rounded-pill mx-4 px-4"> Cancel</a>
                          <button type="submit"  class="btn btn-primary rounded-pill px-4"> Save</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

