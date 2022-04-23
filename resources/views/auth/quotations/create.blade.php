@extends('layouts.sidebar')
@section('page-specific-js')
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">CREATE NEW</a></div>
            </div>
            <div class="card pt-0">
                <livewire:list-product-quotations :pr_number="$pr_number" />
            </div>
        </div>
    </div>
</div>
@endsection
@section('custom-non-head-js')
<script>
   $('.deadline').datepicker({ 
        startDate: "+1d"
    });

   $('.datepicker').datepicker();
</script>
@endsection