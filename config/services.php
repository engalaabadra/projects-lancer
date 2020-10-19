<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_CALLBACK'),
    ],
    'google' => [

        // "client_id" => "668655595438-hkdi3dr3kv21oq0kfim8j85oufdq2fkr.apps.googleusercontent.com",
        // "client_secret" => "bOFd01o50tlXHDGsDW9cxM2b",
        // "redirect" => "http://localhost/flixy/public/login/google/callback"
        'client_id' => '760793908159-i3cni7n0qrmh1lctcg9mheb85nja0mq3.apps.googleusercontent.com',
        'client_secret' => 'Rw8iDXDfPfFWEu7vni8vR8pB',
        'redirect' => 'http://127.0.0.1:8000/user/auth/google/callback',
        // 'client_id' => env('GOOGLE_CLIENT_ID'),
        // 'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        // 'redirect' => env('GOOGLE_CALLBACK'),
        
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CALLBACK'),
    ],
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID',''),
        'client_secret' => env('PAYPAL_CLIENT_SECRET',''), 
        'mode'=>env('PAYPAL_MODE','sandbox')
    ],
    

];
