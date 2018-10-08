<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/27
 * Time: 19:55
 */

namespace app\common\library;

use think\Cache;

class Auth
{
    /**
     * @param $password
     * @return string
     */
    public static function encryptPassword($password)
    {
        return md5($password . config('password_salt'));
    }

    /**
     * 生成每次请求的signature
     * @param array $data time,device_id,app_type,version
     * @return string
     */
    public static function generateSignature($data = [])
    {
        // 1 按字段排序
        ksort($data);
        // 2拼接字符串数据  &
        $string = http_build_query($data);
        // 3通过aes来加密
        $string = (new Aes())->encrypt($string);

        return $string;
    }

    /**
     * 检查signature是否正常
     * @param array $data
     * @return bool
     */
    public static function checkSignature($data)
    {
        $str = (new Aes())->decrypt($data['signature']);

        if (empty($str)) {
            return false;
        }

        // deviceid=xx&
        parse_str($str, $arr);
        if (!is_array($arr) || empty($arr['device_id']) || $arr['device_id'] != $data['device_id']) {
            return false;
        }
        if (!config('app_debug')) {
            if (abs(time() - ceil($arr['time'] / 1000)) > config('time_out')) {
                return false;
            }
            // 唯一性判定
            if (Cache::get($data['signature'])) {
                return false;
            }
        }
        return true;
    }
}