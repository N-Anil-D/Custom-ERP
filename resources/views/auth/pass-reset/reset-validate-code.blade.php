@extends('auth.main')

@section('content')
    <div id="login-button" style="display: none">
    </div>

    <div id="container" class="validate-code">
        <h1>{{ env('APP_NAME') }}</h1>

        <form method="POST" action="{{ route('password.validate.code') }}">
            @csrf
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <input id="tel_no" type="text" name="tel_no" value="{{ $telNo }}" required />
            <input id="validate_code" type="text" name="validate_code" value="{{ old('validate_code') }}" inputmode="tel" placeholder="Doğrulama kodunuz" required />
            <button type="submit">Doğrula</button>
            <a class="back-login-page" href="{{ route('login') }}"><i class="fas fa-home"></i></a>
        </form>
    </div>

@endsection
