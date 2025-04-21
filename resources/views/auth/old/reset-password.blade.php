<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="title" content="{{ config('app.name') }}" />

        <title>{{ config('app.name', 'CustomERP') }}</title>
        <link rel="icon" href="{{ url('img/icon.png') }}" />
        <link rel="shortcut icon" href="{{ url('img/icon.png') }}" type="image/x-icon" />

        <link rel="stylesheet" href="{{ url('login-file/style.css') }}" />

        <script src="{{ url('login-file/jquery.min.js') }}"></script>
        <script src="{{ url('login-file/TweenMax.min.js') }}"></script>

        <!--alertify-->        
        <script src="{{ url('panel/alertify/alertify.min.js') }}"></script>
        <link rel="stylesheet" href="{{ url('panel/alertify/alertify.min.css') }}"/>
        <link rel="stylesheet" href="{{ url('panel/alertify/default.min.css') }}"/>
        <link rel="stylesheet" href="{{ url('panel/alertify/semantic.min.css') }}"/>
        <link rel="stylesheet" href="{{ url('panel/alertify/bootstrap.min.css') }}"/>
        <!--alertify end-->

        {!! htmlScriptTagJsApi([
            'action' => 'homepage',
        ]) !!}
        <script src="https://www.google.com/recaptcha/api.js?hl=en"></script>
        
    </head>

<body>

    <div id="login-button" style="display: none">
    </div>

    <div id="container" style="height: 260px;">
        <h1>{{ env('APP_NAME') }}</h1>
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
            <input type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="E-posta" required  />

            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="parolanız"/>
            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="parola onayınız" />


            <button type="submit">Parolamı sıfırla</button>
        </form>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'validate_captcha'}).then(function (token) {
                $('#g-recaptcha-response').val(token);
            });
        });
    </script>

    @php
        $errorMessages = "";
        if($errors->any()){
            foreach($errors->all() as $error){
                $errorMessages .= '<li>'.$error.'</li>';
            }
        }
    @endphp

    <script>
        alertify.set('notifier', 'position', 'top-center', 10);

        @if($errors->any())
            alertify.error("{!! $errorMessages !!}", 10);
        @endif

    </script>

    <script src="{{ url('login-file/style.js') }}"></script>
    </body>

</html>



