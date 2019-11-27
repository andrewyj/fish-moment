<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

class WeixinService
{
    private $client;
    private $key;
    private $secret;
    private $errorMessage;
    
    const ACCESS_TOKEN_CACHE_NAME = 'access_token';
    
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
    
    public function photoCheck(UploadedFile $photo) {
        $url = 'https://api.weixin.qq.com/wxa/img_sec_check?access_token=' . $this->getToken();
        $filePath = $photo->getRealPath();
        $fileData = array("media"  => new \CURLFile($filePath));
        $ch = curl_init();
        curl_setopt($ch , CURLOPT_URL , $url);
        curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch , CURLOPT_POST, 1);
        curl_setopt($ch , CURLOPT_POSTFIELDS, $fileData);
        $output = json_decode(curl_exec($ch));
        logPlus('photo check data', ['output' => $output], 'wechat');
        curl_close($ch);
    
        if (isset($output->errcode) && $output->errcode == 0) {
            return true;
        }
        
        return false;
    }
    
    public function getToken() {
        $accessToken = Cache::get(self::ACCESS_TOKEN_CACHE_NAME);
        if ($accessToken) {
            return $accessToken;
        }
        $options = ['query' => [
            'appid'      => $this->key,
            'secret'     => $this->secret,
            'grant_type' => 'client_credential',
        ]];
        $response = $this->client->get('/cgi-bin/token', $options);
        if ($response->getStatusCode() == 200) {
            $result = json_decode($response->getBody());
            if (!isset($result->access_token)) {
                logPlus('wechat access token fetch error', ['errorMessage' => $result], 'wechat');
                $this->errorMessage = 'access token 获取失败';
            
                return false;
            }
            $accessToken = $result->access_token;
            Cache::put(self::ACCESS_TOKEN_CACHE_NAME, $accessToken, $result->expires_in);
        
            return $accessToken;
        }
        $this->errorMessage = '请求失败';
    
        return false;
    }
    
    public function getErrorMessage() {
        return $this->errorMessage;
    }
}