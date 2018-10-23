<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/21
 * Time: 8:59
 */

return [
    'app_debug'     => false,
    'email'         => [
        'host'       => '',
        'port'       => 465,
        'username'   => '',
        'password'   => '',
        'smtp_debug' => 0,
    ],
    'time_out'      => 60,//客户端时间与服务端时间差异允许范围
    'app_scope'     => '',
    'token_salt'    => '',
    'password_salt' => '',
    'miniprogram'   => [
        'appid'  => '',
        'secret' => '',
    ],
    'aes'           => [
        'key'        => '',
        'iv'         => '',
        'block_size' => 16,
    ],
    'code'          => [
        'success' => 0,
    ],
    'apptypes'      => [],
    'signature_cache_time' => 120,
    'token_cache_time' => 7200,
    'easywechat' => [
        'miniprogram' => [
            'app_id' => '',
            'secret' => '',

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            /*'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/wechat.log',
            ],*/
        ]
    ],
    'aip_image' => [
        'app_id' => '',
        'api_key' => '',
        'secret' => '',
    ]
];