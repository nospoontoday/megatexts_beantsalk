@extends('layouts.sidebar')


@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Show E-Journals</a></div>
              </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                    <div>    
                        <div class="pull-left">
                            <h2> Show E-Journals</h2>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Book Cover</strong>
                            <img src="{{ asset('products/' . $eJournal->photo->uri) }}" alt="Book Cover" width="60px">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>E-ISSN:</strong>
                            {{ $eJournal->e_issn }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Editor:</strong>
                            {{ $eJournal->editor->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Publisher:</strong>
                            {{ $eJournal->publisher->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Platform:</strong>
                            {{ $eJournal->platform->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Access Model:</strong>
                            {{ $eJournal->accessModel->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $eJournal->product->title }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Frequency:</strong>
                            {{ $eJournal->frequency }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Subscription Period:</strong>
                            {{ $eJournal->subscription_period }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $eJournal->product->quantity }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Unit Price:</strong>
                            {{ $eJournal->product->price }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $eJournal->total_amount }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                        <div class="form-group">
                            <strong>Subject:</strong>
                            {{ $eJournal->product->subject }}
                        </div>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ url('digital/e-journals') }}"> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection