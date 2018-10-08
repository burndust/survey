<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/28
 * Time: 7:40
 */

namespace app\api\controller\v1;

use app\common\library\Aes;
use app\common\library\Token;
use think\Cache;
use think\Controller;

class Login extends Controller
{
    public function index(){
        $token = Token::generate();
        Cache::set($token,1,config('token_cache_time'));
        $token = (new Aes())->encrypt('1234||1');
        return show(['token' => $token]);
    }
}