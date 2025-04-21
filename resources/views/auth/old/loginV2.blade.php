<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'CustomERP') }}</title>
    <link rel="icon" href="{{ url('img/icon.png') }}" />
    <link rel="shortcut icon" href="{{ url('img/icon.png') }}" type="image/x-icon" />


    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css?family=Hind:300' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="{{ url('login-file/styleV2.css') }}" />

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



</head>

<body>

<div id="login-button">
    <img src="{{ url('login-file/img/login-w-icon.png') }}">
    </img>
</div>

<div id="container">
    <h1>CustomERP</h1>
    <span class="close-btn">
        <img src="{{ url('login-file/img/circle_close_delete_-128.webp') }}"></img>
    </span>

    <form method="POST" action="{{ route('login') }}">
        @csrf


        <input type="email" name="email" :value="old('email')" autocomplete="current-email" placeholder="E-posta" required  />
        <input type="password" name="password" required autocomplete="current-password" placeholder="Parola" />
        
        <button type="submit">Giriş Yap</button>
        <div id="remember-container">
            <span id="forgotten">Parolamı unuttum / Bilmiyorum ?</span>
        </div>
    </form>
</div>

<!-- Forgotten Password Container -->
<div id="forgotten-container">
    <h3>Parolamı unuttum</h3>
    <span class="close-btn">
        <img src="{{ url('login-file/img/circle_close_delete_-128.webp') }}"></img>
    </span>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" :value="old('email')" required autofocus placeholder="E-posta" />
        <button type="submit" class="orange-btn">Parolamı Sıfırla</button>
    </form>
</div>




</body>

@error('email')
<script>
alertify.set('notifier', 'position', 'top-center', 10);
alertify.error("{{ $message }}", 10);
</script>
@enderror

@if (session('status'))
<script>
    alertify.set('notifier', 'position', 'top-center', 10);
    alertify.success("{{ session('status') }}", 10);
</script>
@endif

<script src="{{ url('login-file/style.js') }}"></script>


</html>
