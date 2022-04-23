@extends('layouts.sidebar')
@section('page-specific-css')
<style>
.customer-link:hover {
  background-color: #1f386f;
  color: #fff;
  cursor: pointer;
}    
.customer-group{
    position:absolute;
    width:30%;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Add {{ $title }}</a></div>
            </div>
            <div class="card pt-0">
                <livewire:is :component="$componentName" :branches="$branches">
            </div>
        </div>
    </div>
</div>
@endsection