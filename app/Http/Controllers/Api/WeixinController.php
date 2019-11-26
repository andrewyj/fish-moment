<?php

namespace App\Http\Controllers\Api;

use App\Services\WeixinService;

class WeixinController extends BaseController
{
    /**
     * @SWG\Post(
     *     path="/weixin/photo/check",
     *     summary="图片检测",
     *     tags={"weixin"},
     *     description="图片检测",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="上传图片",
     *         in="formData",
     *         name="photo",
     *         required=true,
     *         type="file"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="返回结果",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *             @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="verify_status", type="boolean", description="验证状态 1：验证通过 0：验证失败"),
     *             ),
     *         )
     *     )
     * )
     */
    public function photoCheck() {
        $service = new WeixinService();
        
        return $this->responseData([
            'verify_status' => $service->photoCheck(request()->file('photo')),
        ]);
    }
}
