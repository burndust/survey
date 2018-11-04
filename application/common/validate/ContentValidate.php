<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/11/4
 * Time: 14:18
 */

namespace app\common\validate;


class ContentValidate extends BaseValidate
{
    protected $rule = [
        'content' => 'require|isNotEmpty',
    ];
}