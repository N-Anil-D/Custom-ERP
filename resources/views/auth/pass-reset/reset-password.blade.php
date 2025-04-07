@extends('auth.main')

@section('content')
    <div id="login-button" style="display: none">
    </div>

    <div id="container" class="set-password">
        <h1>{{ env('APP_NAME') }}</h1>

        <form method="POST" action="{{ route('password.set') }}">
            @csrf
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <input type="text" name="tel_no" value="{{ $telNo }}" inputmode="tel" placeholder="Telefon No. (555) 123 4567" required />
            <input type="hidden" name="validate_code" value="{{ $validateCode }}">
            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="parolanız" />
            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="parola onayınız" />
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
            alertify.error("{!! $errorMessages !!}", 10);
        @endif
    </script>
@endsection
