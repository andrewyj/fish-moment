<?php
namespace App\Services;

class OssService
{
    private $key;
    private $secret;
    private $host;
    private $callbackUrl;
    private $dirPrefix = 'photos/';
    
    public function __construct()
    {
        $this->key    = env('OSS_KEY_ID');
        $this->secret = env('OSS_KEY_SECRET');
        $this->host   = 'http://'.env('OSS_BUCKET').'.oss-cn-shanghai.aliyuncs.com';
        $this->callbackUrl = route('oss.callback');
    }
    
    public function setDirPrefix($dirPrefix) {
        $this->dirPrefix = $dirPrefix;
    }
    
    public function signature() {
        // $host的格式为 bucketname.endpoint，请替换为您的真实信息。
//        $host = 'http://bucket-name.oss-cn-hangzhou.aliyuncs.com';
        // $callbackUrl为上传回调服务器的URL，请将下面的IP和Port配置为您自己的真实URL信息。
//        $callbackUrl = 'http://88.88.88.88:8888/aliyun-oss-appserver-php/php/callback.php';
    
        $base64CallbackBody = base64_encode(json_encode([
            'callbackUrl'      => $this->callbackUrl,
            'callbackBody'     => 'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
            'callbackBodyType' => "application/x-www-form-urlencoded"
        ]));
    
        $now    = time();
        $expire = 300;  //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问。
        $end    = $now + $expire;
        $expiration = $this->gmtIso8601($end);
    
        //最大文件大小.用户可以自己设置
        $conditions[] = ['content-length-range', 0, 1048576000];
    
        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        $conditions[] = ['starts-with', '$key', $this->dirPrefix];
    
    
        $base64Policy = base64_encode(json_encode([
            'expiration'=>$expiration,
            'conditions'=>$conditions
        ]));
        $stringToSign = $base64Policy;
        $signature    = base64_encode(hash_hmac('sha1', $stringToSign, $this->secret, true));
    
        $response['accessid']  = $this->key;
        $response['host']      = $this->host;
        $response['policy']    = $base64Policy;
        $response['signature'] = $signature;
        $response['expire']    = $end;
        $response['callback']  = $base64CallbackBody;
        $response['dir']       = $this->dirPrefix;  // 这个参数是设置用户上传文件时指定的前缀。
        
        return $response;
    }
    
    /**
     * 回调验证
     * @return bool
     */
    public function verify() {
        // 1.获取OSS的签名header和公钥url header
        $authorizationBase64 = request()->server('HTTP_AUTHORIZATION');
        $pubKeyUrlBase64     = request()->server('HTTP_X_OSS_PUB_KEY_URL');
        logPlus('oss callback server params', ['params' => request()->server()], 'oss');
        /*
         * 注意：如果要使用HTTP_AUTHORIZATION头，你需要先在apache或者nginx中设置rewrite，以apache为例，修改
         * 配置文件/etc/httpd/conf/httpd.conf(以你的apache安装路径为准)，在DirectoryIndex index.php这行下面增加以下两行
            RewriteEngine On
            RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization},last]
         * */
        if ($authorizationBase64 == '' || $pubKeyUrlBase64 == '') {
            return false;
        }
    
        // 2.获取OSS的签名
        $authorization = base64_decode($authorizationBase64);
    
        // 3.获取公钥
        $pubKeyUrl = base64_decode($pubKeyUrlBase64);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pubKeyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $pubKey = curl_exec($ch);
        if ($pubKey == '') {
            return false;
        }
    
        // 4.获取回调body
        $body = file_get_contents('php://input');
    
        // 5.拼接待签名字符串
        $path = request()->server('REQUEST_URI');
        $pos  = strpos($path, '?');
        if ($pos === false) {
            $authStr = urldecode($path)."\n".$body;
        } else {
            $authStr = urldecode(substr($path, 0, $pos)).substr($path, $pos, strlen($path) - $pos)."\n".$body;
        }
    
        // 6.验证签名
        $ok = openssl_verify($authStr, $authorization, $pubKey, OPENSSL_ALGO_MD5);
        if ($ok == 1) {
            return true;
        }
        
        return false;
    }
    
    protected function gmtIso8601($time) {
        $dtStr      = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $pos        = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        
        return $expiration."Z";
    }
}