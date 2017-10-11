<?php

return [

    'url'       => 'logchecker',

    //绝对路径
    'log_paths' => [
        'Jobs' => "/Users/Anna/wwwroot/jobs/storage/logs/laravel-" . date('Y-m-d') . ".log",
        'Pay'  => "/Users/Anna/wwwroot/pay.verystar.cn/storage/logs/laravel-" . date('Y-m-d') . ".log",
        'SDK'  => "/Users/Anna/wwwroot/verypay-sdk/hk.log",
        'Api'  => "/Users/Anna/wwwroot/payapi_online/logs/log-" . date('Y-m-d') . ".log",
        'Mini' => "/Users/Anna/wwwroot/m.pay.verystar.cn/storage/logs/laravel-" . date('Y-m-d') . ".log",
        'Qr'   => "/Users/Anna/wwwroot/qr.pay.verystar.cn/storage/logs/laravel-" . date('Y-m-d') . ".log",
        'Pic'  => "Users/Anna/wwwroot/pic.52inlove.com/storage/logs/laravel-" . date('Y-m-d') . ".log",
    ],
];

