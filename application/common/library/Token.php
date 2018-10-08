<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/26
 * Time: 14:34
 */

namespace app\common\library;


use think\Cache;

class Token
{
    public static function generate()
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        $str = sha1($str);
        return $str;
    }

    /**
     * 检查signature是否正常
     * @param array $data
     * @return bool
     */
    public static function checkSignature($data)
    {
        $signature = md5($data['token'] . $data['time'] . config('token_salt'));
        if ($signature !== $data['signature']) {
            return false;
        }
        if (!config('app_debug')) {
            if (abs(time() - ceil($data['time'] / 1000)) > config('time_out')) {
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