<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/25
 * Time: 23:28
 */

namespace app\common;


interface Constant
{
    const INTERNAL_SERVER_ERROR = 999;//服务器内部错误
    const PARAMETER_INVALID = 1000;//参数不合法
    const SIGNATURE_FAIL = 1001;//校验失败
    const CONTENT_EXCEPTION = 1002;//内容含有敏感词
    const EMAIL_SEND_FAIL = 1003;//邮件发送失败
    const RECORD_NOT_FOUND = 1004;//资源不存在
    const TOKEN_INVALID = 1005;//token无效

    const INTEGRAL_NOT_WORTH = 10002;//积分不足
    const HAD_ANSWERED = 10003;//已填写过该问卷
}