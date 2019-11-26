<?php

namespace App\Http\Controllers\Api;

use App\Services\OssService;

class OssController extends BaseController
{
    /**
     * @SWG\Post(
     *      path="/oss/signature",
     *      tags={"oss"},
     *      summary="获取签名信息",
     *      description="获取签名信息",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Response(
     *          response=200,
     *          description="结果集",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="integer", description="状态码"),
     *              @SWG\Property(property="message", type="string", description="状态信息"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="accessid", type="integer", description="oss key"),
     *                  @SWG\Property(property="host", type="string", description="oss host"),
     *                  @SWG\Property(property="policy", type="string", description="policy"),
     *                  @SWG\Property(property="signature", type="string", description="签名信息"),
     *                  @SWG\Property(property="expire", type="string", description="过期时间"),
     *                  @SWG\Property(property="callback", type="string", description="回调信息"),
     *                  @SWG\Property(property="dir", type="string", description="路径前缀 这个参数是设置用户上传文件时指定的前缀。"),
     *             ),
     *          )
     *      ),
     * )
     */
    public function signature () {
        $service = new OssService();
        $response = $service->signature();
        
        return response()->json($response);
    }
    
    public function callback() {
        $service = new OssService();
        $result = $service->verify();
        if (!$result) {
            return $this->responseFailed();
        }
        
        return response()->json(['Status' => 'Ok']);
    }
}
