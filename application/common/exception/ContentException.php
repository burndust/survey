<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/23
 * Time: 18:15
 */

namespace app\common\exception;


class ContentException extends BaseException
{
    protected $message = '您输入的信息含有敏感内容';
    protected $code = self::CONTENT_EXCEPTION;
    protected $statusCode = 400;
}