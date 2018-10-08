<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/18
 * Time: 23:18
 */

namespace app\common\exception;

use app\common\Constant;
use think\Exception;

class BaseException extends Exception implements Constant
{
    protected $statusCode;

    /**
     * BaseException constructor.
     * @param string $message
     * @param int $code
     * @param int $statusCode
     */
    public function __construct($message = '', $code = 0, $statusCode = 0)
    {
        if (!empty($message)) {
            $this->message = $message;
        }
        if (!empty($code)) {
            $this->code = $code;
        }
        if (!empty($statusCode)) {
            $this->statusCode = $statusCode;
        }
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}