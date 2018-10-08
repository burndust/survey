<?php

namespace app\index\controller;

use app\common\library\Aes;
use GuzzleHttp\Client;

class Index
{
    public function index()
    {
        $this->test();
        return json([]);
    }

    public function test(){
        return true;
    }
}
