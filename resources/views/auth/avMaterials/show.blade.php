@extends('layouts.sidebar')
@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Show AV Materials</a></div>
              </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                    <div>    
                        <div class="pull-left mb-4">
                            <h2> Show AV Materials</h2>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Book Cover</strong>
                            <img src="{{ asset('products/' . $photo) }}" alt="Book Cover" width="60px">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Item Code:</strong>
                            {{ $avMaterial->item_code }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Author:</strong>
                            {{ $avMaterial->author->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Publisher:</strong>
                            {{ $avMaterial->publisher->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $avMaterial->product->title }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Pub Yr:</strong>
                            {{ $avMaterial->publication_year }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $avMaterial->product->quantity }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Unit Price:</strong>
                            {{ $avMaterial->product->price }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Discount:</strong>
                            {{ $avMaterial->discount }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $avMaterial->total_amount }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>Subject:</strong>
                            {{ $avMaterial->product->subject }}
                        </div>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ url('print/av-materials') }}"> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection