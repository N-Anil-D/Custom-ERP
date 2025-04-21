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

    <link rel="stylesheet" href="{{ url('login-file/style.css'.env('CSSV')) }}" />

    <script src="{{ url('login-file/jquery.min.js') }}"></script>
    <script src="{{ url('login-file/TweenMax.min.js') }}"></script>

    <link rel="stylesheet" href="{{ url('panel/vendor/font-awesome/css/all.min.css') }}" />

    <!--alertify-->
    <script src="{{ url('panel/alertify/alertify.min.js') }}"></script>
    <link rel="stylesheet" href="{{ url('panel/alertify/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ url('panel/alertify/default.min.css') }}" />
    <link rel="stylesheet" href="{{ url('panel/alertify/semantic.min.css') }}" />
    <link rel="stylesheet" href="{{ url('panel/alertify/bootstrap.min.css') }}" />
    <!--alertify end-->

    {!! htmlScriptTagJsApi([
        'action' => 'homepage',
    ]) !!}
    <script src="https://www.google.com/recaptcha/api.js?hl=en"></script>

</head>

<body>

    <script>
        alertify.set('notifier', 'position', 'top-center', 15);
        
        @if (session('success'))
            alertify.success("{{ Session::get('success') }}", 15);
        @endif
        
        @if (session('error'))
            alertify.error("{{ Session::get('error') }}", 15);
        @endif
    </script>

    @yield('content')

    


    <script src="{{ url('login-file/imask.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {
                action: 'validate_captcha'
            }).then(function(token) {
                $('#g-recaptcha-response').val(token);
            });
        });

        var maskElement = document.getElementById('tel_no');
        var maskOptions = {
            mask: '(000) 000 0000'
        };
        var mask = IMask(maskElement, maskOptions);

        var maskElement2 = document.getElementById('tel_no2');
        var maskOptions2 = {
            mask: '(000) 000 0000'
        };
        var mask = IMask(maskElement2, maskOptions2);
    </script>

    <script src="{{ url('login-file/style.js') }}"></script>
</body>

</html>
