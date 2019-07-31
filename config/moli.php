<?php

return [

    'blog' => [

        'url' => env('MOLi_BLOG_URL', 'YOUR-URL'),

        'client_id' => env('MOLi_BLOG_CLIENT_ID', 'YOUR-CLIENT-ID'),

        'client_secret' => env('MOLi_BLOG_CLIENT_SECRET', 'YOUR-CLIENT-SECRET'),

    ],

    'dvr' => [

        'control_url' => env('DVR_BASE_URL', 'YOUR-URL'),

        'snapshot_url' => env('DVR_SHOT', 'YOUR-URL'),

    ],

    'rpos' => [

        'snapshot_url' => env('SCREEN_SHOT', 'YOUR-URL'),

    ],

];
