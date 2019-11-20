<?php
namespace App\Services;

use GuzzleHttp\Client;

class WeixinService
{
    private $client;
    private $key;
    private $secret;
    private $errorMessage;
    
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.weixin.qq.com/']);
        $this->key    = env('WEIXIN_KEY');
        $this->secret = env('WEIXIN_SECRET');
    }
    
    public function getOpenId($code) {
        $options = ['query' => [
            'appid'      => $this->key,
            'secret'     => $this->secret,
            'js_code'    => $code,
            'grant_type' => 'authorization_code',
        ]];
        $response = $this->client->get('sns/jscode2session', $options);
        
        if ($response->getStatusCode() == 200) {
            $result = json_decode($response->getBody());
            if (!isset($result->openid)) {
                logPlus('wechat openid fetch error', ['errorMessage' => $result], 'wechat');
                $this->errorMessage = '参数错误，未获取到用户信息';
                
                return false;
            }
            
            return $result->openid;
        }
        $this->errorMessage = '请求失败';
        
        return false;
    }
    
    public function getErrorMessage() {
        return $this->errorMessage;
    }
}