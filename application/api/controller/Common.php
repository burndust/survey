<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/27
 * Time: 19:47
 */

namespace app\api\controller;

use app\common\Constant;
use app\common\exception\ParameterException;
use app\common\library\Auth;
use think\Cache;
use think\Controller;
use think\Request;

class Common extends Controller implements Constant
{
    public $header = '';
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->checkRequest();
    }

    /**
     * 检查每次app请求的数据是否合法
     */
    public function checkRequest() {
        // 首先需要获取headers
        $header = request()->header();
        // 基础参数校验
        if(empty($header['signature'])) {
            throw new ParameterException('缺少signature');
        }
        if(!in_array($header['app_type'], config('apptypes'))) {
            throw new ParameterException('app_type不合法');
        }

        // 需要signature
        if(!Auth::checkSignature($header)) {
            throw new ParameterException('signature验证失败', self::SIGNATURE_FAIL , 401);
        }
        Cache::set($header['signature'], 1, config('signature_cache_time'));
        $this->header = $header;
    }
}