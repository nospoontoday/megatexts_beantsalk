@extends('layouts.sidebar')


@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Show Print Book</a></div>
              </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                    <div>    
                        <div class="pull-left">
                            <h2> Show Print Book</h2>
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
                            <strong>ISBN 13:</strong>
                            {{ $printBook->isbn_13 }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Author:</strong>
                            {{ $printBook->author->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Publisher:</strong>
                            {{ $printBook->publisher->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $printBook->product->title }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Pub Yr:</strong>
                            {{ $printBook->publication_year }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $printBook->product->quantity }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Unit Price:</strong>
                            {{ $printBook->product->price }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Discount:</strong>
                            {{ $printBook->discount }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $printBook->total_amount }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>Subject:</strong>
                            {{ $printBook->product->subject }}
                        </div>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ url('print/print-books') }}"> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection