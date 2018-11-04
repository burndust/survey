<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/11/4
 * Time: 16:10
 */

namespace app\common\validate;


class NameValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
    ];
}