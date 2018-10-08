<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/26
 * Time: 12:08
 */

namespace app\common\validate;


class TokenValidate extends BaseValidate
{
    protected $rule = [
        'token'     => 'require',
        'time'      => 'require',
        'signature' => 'require',
    ];
    protected $message = [
        'token.require'     => 'token不能为空',
        'time.require'      => 'time不能为空',
        'signature.require' => 'signature不能为空',
    ];
}