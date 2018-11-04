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
}