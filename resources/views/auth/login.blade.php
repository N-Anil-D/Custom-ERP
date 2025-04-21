@extends('auth.main')

@section('content')
    <div id="login-button">
        <h1>Giriş Yap</h1>
    </div>

    <div id="container">
        <h1>{{ config('app.name') }}</h1>

        <span class="close-btn">
            <img src="{{ url('login-file/img/circle_close_delete_-128.webp') }}"></img>
        </span>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <h6>TEL NO : (999) 999 9999</h6>
            <input id="tel_no" type="text" name="tel_no" value="{{ old('tel_no') }}" inputmode="tel"
            placeholder="Telefon No. (999) 999 9999" required />
            <h6>PW : test123456</h6>
            <input id="pw" type="password" name="password" required autocomplete="current-password" placeholder="Parola" />

            <button type="submit">Giriş Yap</button>
            <div id="remember-container">
                <span id="forgotten">Kullanıcı adımı / Parolamı bilmiyorum ?</span>
            </div>
        </form>
    </div>

    <div id="forgotten-container">
        <h3>Parolamı unuttum</h3>

        <span class="close-btn">
            <img src="{{ url('login-file/img/circle_close_delete_-128.webp') }}" />
        </span>

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <input id="tel_no2" type="text" name="tel_no" value="{{ old('tel_no') }}" inputmode="tel"
                placeholder="Telefon No. (999) 999 9999" required />
            <button type="submit" class="orange-btn">Parolamı Sıfırla</button>
        </form>
        <a class="back-login-page" href="{{ route('emailToTel.email.validate') }}">Kullanıcı adınız (Telefon No.) kayıtlı değilse tıklayınız.</a>
    </div>

    <script>
        @error('tel_no')
            alertify.error("{{ $message }}", 10);
        @enderror      
    </script>
@endsection
