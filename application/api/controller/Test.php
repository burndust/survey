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
        $client = initAipImageCensor();
        $result = $client->antiSpam('你真好看');
        return show($result);
    }
}