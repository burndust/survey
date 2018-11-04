<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/26
 * Time: 17:20
 */

namespace app\api\controller\v1;

use app\common\Constant;
use app\common\exception\BaseException;
use app\common\exception\ParameterException;
use app\common\library\Aes;
use app\common\library\Token;
use app\common\model\User;
use think\Cache;
use think\Controller;
use think\Request;

class Base extends Controller implements Constant
{
    /**
     * 登录用户的基本信息
     * @var array
     */
    protected $user = [];
    /**
     * 是否检测登录状态,本地测试时关闭，上线状态为打开
     * @var bool
     */
    protected $checkLogin = false;
    protected $params;

    /**
     * 初始化
     *
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->user = User::get(4);//上线状态需将此段内容注释掉
        $this->checkLogin && $this->isLogin();
        $method       = request()->method();
        $this->params = camelToUnderLineArr(request()->$method());
    }

    /**
     * 若未继承Common类，则使用此方法判定是否登录
     * @return bool
     * @throws ParameterException
     */
    public function isLogin()
    {
        $header = request()->header();
        if (empty($header['token']) || empty($header['time']) || empty($header['signature'])) {
            throw new ParameterException('header头部信息缺少参数');
        }
        if (!Token::checkSignature($header)) {
            throw new ParameterException('signature验证失败', self::SIGNATURE_FAIL, 401);
        }
        Cache::set($header['signature'], 1, config('signature_cache_time'));
        // 传给前端的为加密的token,存储在缓存中的为未加密前的token $encrypt = encrypt($token . '||' . $userId)
        $token = (new Aes())->decrypt($header['token']);
        if (empty($token) || !preg_match('/||/', $token)) {
            throw new ParameterException('token无效');
        }
        list($token, $id) = explode("||", $token);
        if (!Cache::get($token)) {
            throw new ParameterException('token不存在');
        }
        $user = User::get($id);
        if (!$user) {
            throw new BaseException('用户不存在', self::RECORD_NOT_FOUND, 404);
        }
        $this->user = $user;

        return true;
    }

    /**
     * 若继承了Common类，则使用此方法判定是否登录
     * @return bool
     * @throws ParameterException
     */
    /*public function isLogin() {
        if(empty($this->header['token'])) {
            throw new ParameterException('缺少token');
        }
        // 传给前端的为加密的token,存储在缓存中的为未加密前的token $encrypt = encrypt($token . '||' . $userId)
        $aes = new Aes();
        $token = $aes->decrypt($this->header['token']);
        if(empty($token) || !preg_match('/||/', $token)) {
            throw new ParameterException('token无效');
        }
        list($token, $id) = explode("||", $token);
        if(!Cache::get($token)){
            throw new ParameterException('token不存在');
        }
        $user = User::get($id);
        if(!$user || $user->status != 1) {
            throw new BaseException('用户不存在',self::RECORD_NOT_FOUND, 404);
        }
        $this->user = $user;

        return true;
    }*/
}