<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/20
 * Time: 13:37
 */

namespace app\common\validate;


class IntegralValidate extends BaseValidate
{
    protected $rule = [
        'integral' => 'require|isPositiveInteger',
    ];
}