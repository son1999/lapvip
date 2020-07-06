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
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '292240374891860',
        'client_secret' => 'd65f7db1bed7ddc3058b696a90e69678',
        'redirect' => 'http://emarket.tannv.net/auth/facebook/callback',
    ],

    'google' => [
        'client_id' => '10503181821-uchcvr35ruqip3hfpigm0f8b6kkajbij.apps.googleusercontent.com',
        'client_secret' => 'p6P0Wm4P__dzIF-5PytETCx4',
        'redirect' => 'http://emarket.tannv.net/auth/google/callback',
    ],
];
