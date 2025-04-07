@extends('auth.main')

@section('content')
    <div id="login-button" style="display: none">
    </div>

    <div id="container" class="email-validate" style="width: 420px; height: 230px;">
        <h1>{{ env('APP_NAME') }}</h1>

        <form method="POST" action="{{ route('emailToTel.set.telno') }}">
            @csrf
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <input type="hidden" name="validate_code" value="{{ $token->validate_code }}">
            <input type="email" name="email" value="{{ $token->email }}" placeholder="E-posta adresiniz" required />
            <input id="tel_no" type="text" name="tel_no" value="{{ old('tel_no') }}" inputmode="tel" placeholder="Telefon No. (555) 123 4567" required />
            <button type="submit">Onayla</button>
            <a class="back-login-page" href="{{ route('login') }}"><i class="fas fa-home"></i></a>
        </form>
    </div>

    @php
        $errorMessages = "";
        if($errors->any()){
            foreach($errors->all() as $error){
                $errorMessages .= '<li>'.$error.'</li>';
            }
        }
    @endphp

    <script>
        @if($errors->any())
            alertify.error("{!! $errorMessages !!}", 15);
        @endif
    </script>

@endsection
