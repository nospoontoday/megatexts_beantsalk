@extends('layouts.sidebar')


@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Show Print Journal</a></div>
              </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                    <div>    
                        <div class="pull-left mb-4">
                            <h2> Show Print Journal</h2>
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
                            <strong>ISSN:</strong>
                            {{ $printJournal->issn }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Editor:</strong>
                            {{ $printJournal->editor->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $printJournal->product->title }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Issue No.:</strong>
                            {{ $printJournal->issue_number }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Unit Price per Issue:</strong>
                            {{ $printJournal->product->price }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $printJournal->product->quantity }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $printJournal->total_amount }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>Subject:</strong>
                            {{ $printJournal->product->subject }}
                        </div>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ url('print/print-journals') }}"> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection