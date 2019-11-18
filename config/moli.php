<?php

return [

    'blog' => [

        'domain' => 'https://blog.moli.rocks',

        'key' => env('MOLI_BLOG_KEY'),

    ],

    'dvr' => [

        'control_url' => env('DVR_BASE_URL'),

        'snapshot_url' => env('DVR_SHOT'),

    ],

    'rpos' => [

        'snapshot_url' => env('SCREEN_SHOT'),

    ],

];
