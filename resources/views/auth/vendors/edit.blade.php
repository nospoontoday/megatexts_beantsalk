@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">EDIT VENDOR</a></div>
            </div>
            <div class="card pt-0">
                <livewire:edit-product-vendors :vendor="$vendor" />
            </div>
        </div>
    </div>
</div>
@endsection

