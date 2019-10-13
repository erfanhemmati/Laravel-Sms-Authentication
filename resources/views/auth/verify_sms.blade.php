@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('فعال سازی حساب کاربری') }}</div>

                    <div class="card-body">

                        @if(session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('doVerify') }}" novalidate>
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input id="code" type="number"
                                           class="form-control @error('code') is-invalid @enderror" name="code"
                                           required placeholder="کد ارسال شده به تلفن همراه">

                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        {{ __('ثبت') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <p class="text-xl-center mt-2">بعد از گذشت 2 دقیقه می توانید نسبت به ارسال مجدد پیامک اقدام کنید.</p>
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('codeSend') }}" method="post">
                            @csrf
                            <input type="hidden" name="phone" id="phone" value="{{ request()->phone }}">
                            <button type="submit" id="btn-send" class="btn btn-primary btn-lg btn-block" disabled>
                                {{ __('درخواست کد جدید') }}
                            </button>
                        </form>
                    </div>

                    <script>
                        var startTime1 = new Date('{{date('Y/m/d H:i:s', time()) }}');
                        var endTime1 = new Date('{{ date('Y/m/d H:i:s', session('sendtime')) }}');

                        function getMinutesBetweenDates(startDate, endDate) {
                            var diff = endDate.getTime() - startDate.getTime();
                            return Math.floor((diff / 60000));
                        }

                        // console.log(getMinutesBetweenDates(endTime1, startTime1));
                        if (getMinutesBetweenDates(endTime1, startTime1) >= 2) {
                            document.getElementById('btn-send').removeAttribute("disabled");
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
