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
    const INTERNAL_SERVER_ERROR = 999;
    const PARAMETER_INVALID = 1000;
    const SIGNATURE_FAIL = 1001;
    const EMAIL_SEND_FAIL = 10000;
    const RECORD_NOT_FOUND = 10001;
    const INTEGRAL_NOT_WORTH = 10002;
    const HAD_ANSWERED = 10003;
}