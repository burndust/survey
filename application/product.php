<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/21
 * Time: 8:59
 */

return [
    'app_debug'            => false,
    'email'                => [
        'host'       => '',
        'port'       => 465,
        'username'   => '',
        'password'   => '',
        'smtp_debug' => 0,
    ],
    'time_out'             => 60,//客户端时间与服务端时间差异允许范围
    'app_scope'            => '',//session作用域
    'token_salt'           => '',//token盐
    'password_salt'        => '',//密码盐
    'aes'                  => [
        'key'        => '',//aes key
        'iv'         => '',
        'block_size' => 16,
    ],
    'code'                 => [
        'success' => 0,//请求成功时的返回码
    ],
    'apptypes'             => [],//未使用
    'signature_cache_time' => 120,//token验证缓存
    'token_cache_time'     => 7200,//token缓存时间
    'easywechat'           => [
        //小程序app_id和app_secret
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
    //百度内容审核调用所需信息
    'aip_image'            => [
        'app_id' => '',
        'api_key' => '',
        'secret' => '',
    ]
];