<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/10/23
 * Time: 13:10
 */

namespace app\api\controller;

class Test
{
    public function test(){
//        $client = initAipImageCensor();
//        $result = $client->antiSpam('你真好看');

        $app    = \EasyWeChat\Factory::miniProgram(config('easywechat.miniprogram'));
        $result = $app->content_security->checkText('习近平');
        if(isset($result['errcode']) && 87014 == $result['errcode']){
            throw new \app\common\exception\ContentException();
        }
        return show($result);
    }
}