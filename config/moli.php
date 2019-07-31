<?php

return [

    'blog' => [

        'url' => env('MOLi_BLOG_URL'),

        'client_id' => env('MOLi_BLOG_CLIENT_ID'),

        'client_secret' => env('MOLi_BLOG_CLIENT_SECRET'),

    ],

    'dvr' => [

        'control_url' => env('DVR_BASE_URL'),

        'snapshot_url' => env('DVR_SHOT'),

    ],

    'rpos' => [

        'snapshot_url' => env('SCREEN_SHOT'),

    ],

];
