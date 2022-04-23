@extends('layouts.sidebar')


@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Show Lib-Tech</a></div>
              </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                    <div>    
                        <div class="pull-left">
                            <h2> Show Library Technologies</h2>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Book Cover</strong>
                            <img src="{{ asset('products/' . $libraryTechnology->photo->uri) }}" alt="Book Cover" width="60px">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Item Code:</strong>
                            {{ $libraryTechnology->item_code }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Developer:</strong>
                            {{ $libraryTechnology->developer->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $libraryTechnology->product->title }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Subscription Period:</strong>
                            {{ $libraryTechnology->dimension }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Unit Price:</strong>
                            {{ $libraryTechnology->product->price }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $libraryTechnology->product->quantity }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $libraryTechnology->total_amount }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Vatable Sales:</strong>
                            {{ $libraryTechnology->vatable_sales }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>VAT:</strong>
                            {{ $libraryTechnology->vat }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>Subject:</strong>
                            {{ $libraryTechnology->product->subject }}
                        </div>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ url('digital/library-technologies') }}"> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection