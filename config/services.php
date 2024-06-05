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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'linkaja' => [
        'base_url' => env('LINKAJA_BASEURL'),
        'username' => env('LINKAJA_USERNAME'),
        'secret' => env('LINKAJA_SECRET'),
        'no_dummy' => env('LINKAJA_NO_DUMMY')
    ],

    'iris' => [
        'base_url' => env('IRIS_BASEURL'),
        'api_key_creator' => env('IRIS_APIKEY_CREATOR'),
        'api_key_approval' => env('IRIS_APIKEY_APPROVAL'),
        'auth_string_creator' =>  base64_encode(env('IRIS_APIKEY_CREATOR').":"),
        'auth_string_approval' =>  base64_encode(env('IRIS_APIKEY_APPROVAL').":"),
        'merchant' => env('IRIS_MERCHANT'),
    ],

    'tsat_chat' => [
        'base_url' => env('TSAT_CHAT_BASE_URL')
    ],

    'mantools_backend' => [
        'base_url' => env('MANTOOLS_BACKEND_BASE_URL')
    ]
];
