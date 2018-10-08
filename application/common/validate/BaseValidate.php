<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/20
 * Time: 13:13
 */

namespace app\common\validate;

use app\common\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function __construct(array $rules = [], array $message = [], array $field = [])
    {
        parent::__construct($rules, $message, $field);
        $this->goCheck();
    }

    private function goCheck()
    {
        $request             = Request::instance();
        $params              = $request->param();

        if (!$this->check($params)) {
            throw new ParameterException(is_array($this->error) ? implode(';', $this->error) : $this->error);
        }
        return true;
    }

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

    protected function isMobile($value, $rule = '', $data = '', $field = '')
    {
        $rule   = '^1\d{10}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return $field . '手机号格式不正确';
        }
    }


//    // 令牌合法并不代表操作也合法
//    // 需要验证一致性
//    protected function isUserConsistency($value, $rule, $data, $field)
//    {
//        $identities = getCurrentIdentity(['uid', 'power']);
//        extract($identities);
//
//        // 如果当前令牌是管理员令牌，则允许令牌UID和操作UID不同
//        if ($power == ScopeEnum::Super) {
//            return true;
//        }
//        else {
//            if ($value == $uid) {
//                return true;
//            }
//            else {
//                throw new TokenException([
//                                             'msg' => '你怎么可以用自己的令牌操作别人的数据？',
//                                             'code' => 403,
//                                             'errorCode' => '10003'
//                                         ]);
//            }
//        }
//   }
}