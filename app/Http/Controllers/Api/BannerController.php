<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BannerResource;
use App\Models\Banner;

class BannerController extends BaseController
{
    
    /**
     * @SWG\Get(
     *     path="/banners",
     *     summary="广告图",
     *     tags={"banner"},
     *     description="广告图",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         in="query",
     *         name="codes",
     *         description="banner 码，活动传activity",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="结果集",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *             @SWG\Property(property="data",type="array",
     *                  @SWG\Items(type="object",
     *                      @SWG\Property(property="code_name",type="array",
     *                          @SWG\Items(type="object",
     *                              @SWG\Property(property="id", type="integer", description="banner ID"),
     *                              @SWG\Property(property="title", type="string", description="标题"),
     *                              @SWG\Property(property="introduction", type="string", description="备注"),
     *                              @SWG\Property(property="code", type="string", description="banner code"),
     *                              @SWG\Property(property="picture_url", type="string", description="图片地址"),
     *                              @SWG\Property(property="url", type="string", description="跳转地址"),
     *                              @SWG\Property(property="link_type", type="string", description="跳转类型 0-内链 1-外链"),
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *         )
     *     ),
     * )
     */
    public function banners() {
        $codes = explode(',', request()->get('codes'));
        $banners = Banner::whereIn('code', $codes)
                    ->available()
                    ->orderBy('sort', 'desc')
                    ->get();
        $data = [];
        foreach ($banners as $banner) {
            $data[$banner->code][] = new BannerResource($banner);
        }
        
        return $this->responseData($data);
    }
}
