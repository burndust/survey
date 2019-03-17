<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 通用化API接口数据输出
 * @param $code int 业务状态码
 * @param $message string 信息提示
 * @param $data [] 数据
 * @param $statusCode int http状态码
 * @return \think\response\Json
 */
function show($data, $code = 0, $message = 'ok', $statusCode = 200)
{
    $data = $data ? underLineArrTOCamel($data) : [];
    $data = [
        'code'    => empty($code) ? config('code.success') : $code,
        'message' => $message ? $message : 'ok',
        'data'    => $data,
    ];

    return json($data, $statusCode);
}

/***
 * @param $fields []|object 驼峰对象
 * @return array
 */
function camelToUnderLineArr($fields)
{
    if (is_array($fields)) {
        $fields = (object)$fields;
    }
    $newArr = [];
    if (!is_object($fields) || !get_object_vars($fields)) return $newArr;

    foreach ($fields as $key => &$v) {
        if (is_string($v) && in_array($key, ['name', 'description', 'content'])) {
            $app    = \EasyWeChat\Factory::miniProgram(config('easywechat.miniprogram'));
            $result = $app->content_security->checkText($v);
            if (isset($result['errcode']) && 87014 == $result['errcode']) {
                throw new \app\common\exception\ContentException();
            }
        }
        if (is_string($v)) $v = trim($v);
        if (is_array($v)) {
            $v = camelToUnderLineArr($v);
        }
        $keyTmp          = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $key));
        $newArr[$keyTmp] = $v;
        unset($fields->$key);
    }

    return $newArr;
}

/***
 * @param $fields [] 下划线数组
 * @return array
 */
function underLineArrTOCamel($fields)
{
    $newArr = [];
    if (!is_array($fields) || !$fields) return [];
    foreach ($fields as $key => $v) {
        if (is_array($v)) {
            $v = underLineArrTOCamel($v);
        }
        $keyTmp          = array_reduce(explode('_', $key), function ($v1, $v2) {
            return ucfirst($v1) . ucfirst($v2);
        });
        $keyTmp          = lcfirst($keyTmp);
        $newArr[$keyTmp] = $v;
        unset($fields[$key]);
    }
    return $newArr;
}

function getMillisecond()
{
    list($t1, $t2) = explode(' ', microtime());
    return $t2 . ceil($t1 * 1000);
}

function initAipImageCensor()
{
    $aipImage = config('aip_image');
    return new \AipImageCensor\AipImageCensor($aipImage['app_id'], $aipImage['api_key'], $aipImage['secret']);
}
