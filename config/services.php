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
  		'key' => 'AKIAI5F5QTEETGTD7FJQ',
  		'secret' => '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',
  		'region' => 'us-east-1',
  		'version'  => 'latest',
  		'credentials' => [
  			'key' => 'AKIAI5F5QTEETGTD7FJQ',
  			'secret' => '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'
  		],
  		'enable_verify_peer' => false
  	],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
