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

    'discord' => [
        // OAuth 2.0
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('APP_URL') . '/auth/discord/callback',

        // optional
        'allow_gif_avatars' => (bool) env('DISCORD_AVATAR_GIF', true),
        'avatar_default_extension' => env('DISCORD_EXTENSION_DEFAULT', 'webp'), // only pick from jpg, png, webp

        // Discord API
        'api_token' => env('DISCORD_BOT_TOKEN'),
        'guild_id' => env('DISCORD_GUILD_ID', 884033801334980649),
        'role_id' => env('DISCORD_ROLE_ID', 884063960582721597),
        'webhook_url' => env('DISCORD_VERIFICATION_WEBHOOK')
    ],

    'steam' => [
        'client_id' => null,
        'client_secret' => env('STEAM_API_KEY'),
        'redirect' => env('APP_URL') . '/auth/steam/callback',
        'allowed_hosts' => [
            'localhost',
            'ley-link.test',
            'leystryku.support',
            'beta.leystryku.support',
        ]
    ],

    'gmodstore' => [
        'api_token' => env('GMODSTORE_API_KEY')
    ]
];
