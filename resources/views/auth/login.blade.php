@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <img src="{{ asset('img/login.jpg') }}" class="bg-book-img"></a>
            <div class="card shadow-lg">
                <div class="card-body">
                  
                    <div class="row text-center">
                        <h1 class="fw-bolder p-4 wf">Welcome!</h1>
                        <h5 class="fw-bold text-secondary wf pb-4">Login</h5>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                            
                        <div class="row mb-4 px-4">

                            <div class="col-md-12">
                                <input id="email" placeholder="Email" type="email" class="bg-white rounded-0 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="input-group mb-4 px-4">
                            <input id="password" type="password" name="password" placeholder="Password" type="password" class="form-control bg-white border-end-0 rounded-0" required autocomplete="current-password">
                            <div class="input-group-append">
                                <span toggle="#password-field" class="input-group-text bg-white rounded-0"><a href="#"><i class="field_icon bi bi-eye field_icon toggle-password"></i></a></span>
                            </div>
                        </div>
                       
                        <div class="row mb-4">
                            <div class="text-center">
                                <button type="submit" class="rounded-pill btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
       
    </div>
</div>
@endsection
