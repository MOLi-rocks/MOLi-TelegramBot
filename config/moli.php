<?php

return [
    'telegram' => [

        'group_id' => env('MOLI_GROUP_ID'),

        'group_welcome_msg' =>
            '歡迎來到 MOLi（創新自造者開放實驗室），這裡是讓大家一起創造、分享、實踐的開放空間。' . PHP_EOL . PHP_EOL .
            '以下是一些資訊連結：' . PHP_EOL . PHP_EOL .
            '/* MOLi 相關 */' . PHP_EOL .
            '- MOLi 聊天群 @MOLi_Rocks' . PHP_EOL .
            '- MOLi Bot @MOLiRocks_bot' . PHP_EOL .
            '- MOLi 廣播頻道 @MOLi_Channel' . PHP_EOL .
            '- MOLi 天氣廣播台 @MOLi_Weather'  . PHP_EOL .
            '- MOLi 知識中心 http://hackfoldr.org/MOLi/' . PHP_EOL .
            '- MOLi 首頁 https://MOLi.Rocks' . PHP_EOL .
            '- MOLi Blog https://blog.moli.rocks' . PHP_EOL . PHP_EOL .
            '/* NCNU 相關 */' . PHP_EOL .
            '- 暨大最新公告 @NCNU_NEWS'  . PHP_EOL .
            '- 暨大最新公告 Line 通知申請 https://bot.moli.rocks/line-notify-auth'  . PHP_EOL . PHP_EOL .
            '/* Telegram 相關 */' . PHP_EOL .
            '- Telegram 非官方中文站 https://telegram.how'

    ],

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
