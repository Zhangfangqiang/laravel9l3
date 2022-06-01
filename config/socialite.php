<?php
return [
    'wechat' => [
        'client_id' => env('WEIXIN_APPID'),
        'client_secret' => env('WEIXIN_APPSECRET'),
        'redirect' => env('WEIXIN_REDIRECT_URI'),
    ],
];
