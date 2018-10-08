<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/26
 * Time: 17:19
 */

namespace app\api\controller\v1;

use app\common\exception\ParameterException;
use app\common\library\Aes;
use app\common\library\Token;
use app\common\model\MiniUser;
use app\common\model\User;
use EasyWeChat\Factory;
use think\Cache;

class MiniProgram extends Base
{
    /**
     * 不需要检测登录状态
     * @var bool
     */
    public $checkLogin = false;
    /**
     * @param $code string 调用wx.login获得
     * @param string $rawData string 调用wx.getUserInfo获得
     * @param string $signature string 调用wx.getUserInfo获得
     * @return \think\response\Json
     * @throws ParameterException
     */
    /*public function login($code, $rawData = '', $signature = '')
    {
        $miniProgram = new \MiniProgramLib(config('miniprogram'));
        $response = $miniProgram->login($code);
        //校验签名
        $checkSignature = sha1($rawData.$response['session_key']);
        if ($checkSignature != $signature) {
            throw new ParameterException('校验错误',self::SIGNATURE_FAIL);
        }
        $userInfo = json_decode($rawData,true);
        return show($userInfo);
    }*/

    /*public function login($code, $rawData = '', $signature = '')
    {
        $app      = Factory::miniProgram(config('easywechat.miniprogram'));
        $response = $app->auth->session($code);
        //校验签名
        $checkSignature = sha1($rawData . $response['session_key']);
        if ($checkSignature != $signature) {
            throw new ParameterException('校验错误', self::SIGNATURE_FAIL);
        }
        $userInfo         = json_decode($rawData);
        $userInfo->openid = $response['openid'];
        if ($miniUser = MiniUser::get(['openid' => $response['openid']])) {
            $miniUser->allowField(true)->save($userInfo);
        }else{
            $miniUser = MiniUser::create(camelToUnderLineArr($userInfo), true);
            if ($user = User::create(['integral' => 0])) {
                $miniUser->save(['user_id' => $user['id']]);
            }
        }
        //缓存未加密前的token，传给前端加密后的token值
        $token = Token::generate();
        Cache::set($token, 1, config('token_cache_time'));
        $token = (new Aes())->encrypt($token . '||' . $miniUser['user_id']);
        return show(['token' => $token]);
    }*/

    public function login($code, $rawData = '', $signature = '')
    {
        $app      = Factory::miniProgram(config('easywechat.miniprogram'));
        $response = $app->auth->session($code);
        $miniUser = MiniUser::get(['openid' => $response['openid']]);
        if (!$miniUser) {
            $miniUser = MiniUser::create(['openid' => $response['openid']], true);
            if ($user = User::create(['integral' => 0])) {
                $miniUser['user_id'] = $user['id'];
                $miniUser->save();
            }
        }
        //缓存未加密前的token，传给前端加密后的token值
        $token = Token::generate();
        Cache::set($token, 1, config('token_cache_time'));
        $token = (new Aes())->encrypt($token . '||' . $miniUser['user_id']);
        return show(['token' => $token]);
    }

    public function test()
    {
        $token     = Token::generate();
        $aes       = new Aes();
        $token     = 'H0bpaLCaaVxb4atEZr3DNc28Ea1Xf8LSnaOR7PRKJdz4kGkhvh6WQ5IqhdfzE1aF5UYn9ZXp9dZeTb3F3xHbEQ==';
        $decrypted = $aes->decrypt($token);
        return show(['token' => $token, 'desc' => $decrypted]);
    }
}