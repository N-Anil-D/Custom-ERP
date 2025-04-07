@extends('auth.main')

@section('content')
    <div id="login-button" style="display: none">
    </div>

    <div id="container" class="email-validate" style="width: 420px; height: 185px;">
        <h1>{{ env('APP_NAME') }}</h1>

        <form method="POST" action="{{ route('emailToTel.validate.control') }}">
            @csrf
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="E-posta adresiniz" required />
            <button type="submit">Onayla</button>
            <a class="back-login-page" href="{{ route('login') }}"><i class="fas fa-home"></i></a>
        </form>
    </div>

@endsection
