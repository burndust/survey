<?php
/**
 * Created by PhpStorm.
 * User: luoxm
 * Date: 2018/9/26
 * Time: 12:09
 */

use GuzzleHttp\Client;

class MiniProgram
{
    private $appid;
    private $secret;

    public function __construct($options)
    {
        $this->appid  = isset($options['appid']) ? $options['appid'] : '';
        $this->secret = isset($options['secret']) ? $options['secret'] : '';
    }

    public function login($code)
    {
        $client   = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.weixin.qq.com',
            'verify'   => false,
        ]);
        $query    = [
            'appid'      => $this->appid,
            'secret'     => $this->secret,
            'js_code'    => $code,
            'grant_type' => 'authorization_code',
        ];
        $response = $client->request('GET', '/sns/jscode2session?', ['query' => $query]);
        $body     = $this->json_decode($response);
        return $body;
    }

    private function json_decode($response)
    {
        $body = json_decode($response->getBody(), true);
        if(isset($body['errcode'])){
            throw new \Exception($body['errmsg'],$body['errcode']);
        }else{
            return $body;
        }
    }
}