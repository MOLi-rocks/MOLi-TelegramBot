<?php

return [

    'blog' => [

        'url' => 'https://blog.moli.rocks',

        'client_id' => env('MOLI_BLOG_CLIENT_ID'),

        'client_secret' => env('MOLI_BLOG_CLIENT_SECRET'),

    ],

    'dvr' => [

        'control_url' => env('DVR_BASE_URL'),

        'snapshot_url' => env('DVR_SHOT'),

    ],

    'rpos' => [

        'snapshot_url' => env('SCREEN_SHOT'),

    ],

];
