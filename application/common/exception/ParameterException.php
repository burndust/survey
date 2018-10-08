<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/19
 * Time: 7:49
 */

namespace app\common\exception;

class ParameterException extends BaseException
{
    protected $message = 'invalid parameters';
    protected $code = self::PARAMETER_INVALID;
    protected $statusCode = 400;
}