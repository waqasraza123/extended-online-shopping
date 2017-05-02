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
        'domain' => 'sandbox04bd8d44559e41c3a815b5e62da3c9cc.mailgun.org',
        'secret' => 'key-733e93726f4bba4f63504c7e361745c3',
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => '0a641e98cd258c9e275d1224f33fa038c4ba42a8',
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
