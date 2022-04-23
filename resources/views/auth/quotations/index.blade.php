@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab {{ ! app('request')->input('status') ? 'active' : '' }}"><a class="{{ ! app('request')->input('status') ? 'fw-bold text-primary' : '' }}" href="{{url('quotations')}}">Quotations</a></div>
                <div class="tab {{ app('request')->input('status') && app('request')->input('status') == 'approved' ? 'active' : '' }}"><a class="{{ app('request')->input('status') && app('request')->input('status') == 'approved' ? 'fw-bold text-primary' : '' }}" href="{{url('quotations?status=approved')}}"">Approved Quotations</a></div>
                <div class="tab {{ app('request')->input('status') && app('request')->input('status') == 'cancelled' ? 'active' : '' }}"><a class="{{ app('request')->input('status') && app('request')->input('status') == 'cancelled' ? 'fw-bold text-primary' : '' }}" href="{{url('quotations?status=cancelled')}}"">Cancelled Quotations</a></div>
            </div>
            <div class="card pt-0">
                <div class="card-body p-4">
                      <div class="mb-4">
                        @if(! app('request')->input('status'))
                            <livewire:list-quotations />
                        @elseif(app('request')->input('status') && app('request')->input('status') == 'approved')
                            <livewire:list-approved-quotations />
                        @elseif(app('request')->input('status') && app('request')->input('status') == 'cancelled')
                            <livewire:list-cancelled-quotations />
                        @endif
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection