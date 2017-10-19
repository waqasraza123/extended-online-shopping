<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => '',
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'smtp' => [
        'driver' => env('MAIL_DRIVER', 'smtp'),
        'host' =>env('MAIL_HOST', 'smtp.gmail.com'),
        'port' =>env('MAIL_PORT', 587),
        'from' => ['address' =>'youremail@mail.com', 'name' => 'Email_Subject'],
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'username' =>env('MAIL_USERNAME','yourusername@mail.com'),
        'password' =>env('MAIL_PASSWORD','youremailpassword'),
        'sendmail' =>'/usr/sbin/sendmail -bs',
    ]

];
