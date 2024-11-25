@extends('layouts.app')
@section('content')
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="{{ route('home') }}"><img src="assets/images/logo/company_logo.png" alt="Company logo"></a>
                    </div>
                              
                    <h1 class="auth-title">Lock Screen.</h1>
                    <p class="auth-subtitle mb-5">Lock screen system managenent</p>
                    @if(session()->has('error'))
                        <div class="text-danger text-center text-bold">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <br>
                    <form method="POST" action="{{ route('unlock') }}" class="md-float-material">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Enter Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Lock Screen</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                </div>
            </div>
        </div>
    </div>
@endsection
