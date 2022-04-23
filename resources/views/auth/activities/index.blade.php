@extends('layouts.main')


@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <h4>Activities</h4>
            @foreach($data as $key => $activity)
                <p>{{ $activity }}</p>
            @endforeach
        </div>
    </div>
</div>

@endsection