<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/20
 * Time: 13:37
 */

namespace app\common\validate;


class IdValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];
}