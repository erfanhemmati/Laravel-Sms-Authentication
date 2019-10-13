@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('ثبت نام در وب سایت') }}</div>

                    <div class="card-body">

                        @if(session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" novalidate>
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" required placeholder="نام"
                                           autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="phone" type="number"
                                           class="form-control @error('phone') is-invalid @enderror" name="phone"
                                           value="{{ old('phone') }}" required placeholder="تلفن همراه">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required placeholder="رمز عبور">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <script src='https://www.google.com/recaptcha/api.js?explicit&hl=fa'></script>
                            <div class="form-group row" style="display: flex; justify-content: center">
                                <div class="recaptcha">
                                    @if(env('GOOGLE_RECAPTCHA_KEY'))
                                        <div class="g-recaptcha" style="display: inline-block;"
                                             data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}">
                                        </div>
                                    @endif
                                    <br>
                                    @error('g-recaptcha-response')
                                    <span style="display: flex; justify-content: center;" class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        {{ __('ثبت نام') }}
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
