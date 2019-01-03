<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LINE Notify Client ID [REQUIRED]
    |--------------------------------------------------------------------------
    |
    | Your LINE Notify's Access Client ID.
    | Example: adTHJiKiA39mELihBinK
    |
    | Refer for more details:
    | https://notify-bot.line.me/doc/
    |
    */
    'line_notify_client_id' => env('LINE_NOTIFY_CLIENT_ID', 'YOUR-ID'),

    /*
    |--------------------------------------------------------------------------
    | LINE Notify Client Secret [REQUIRED]
    |--------------------------------------------------------------------------
    |
    | Your LINE Notify's Access Client ID.
    | Example: 3VF2nKo3RdYIUUilOPdUXIZeDfIOPsy0rgSi4
    |
    | Refer for more details:
    | https://notify-bot.line.me/doc/
    |
    */
    'line_notify_client_secret' => env('LINE_NOTIFY_CLIENT_SECRET', 'YOUR-SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Line Notify Redirect URI [REQUIRED]
    |--------------------------------------------------------------------------
    |
    | Your LINE Notify's Authentication page URI.
    | The format should be "https://{YOUR_DOMAIN}/auth"
    | Example: https://api.moli.rocks/line-notify-auth
    |
    | Refer for more details:
    | https://notify-bot.line.me/
    |
    */
    'line_notify_redirect_uri' => env('LINE_NOTIFY_REDIRECT_URI', 'https://bot.moli.rocks/line-notify-auth'),
];
