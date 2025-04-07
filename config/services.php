<?php

return [

    'mailgun'   => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark'  => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses'       => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN', '5222895105:AAF9fct_xvGfUl1s7tnlEozBOLu3jl77DU4')
    ],

];
