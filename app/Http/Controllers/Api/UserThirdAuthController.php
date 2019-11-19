<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserStore;
use App\Models\User;
use App\Services\WeixinService;

class UserThirdAuthController extends BaseController {
    
    /**
     * @SWG\Post(
     *     path="/user-third-auth/wx",
     *     summary="微信登录",
     *     tags={"user-third-auth"},
     *     description="微信登录",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *      @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="code",
     *                  type="string",
     *                  description="微信授权码",
     *              ),
     *              @SWG\Property(
     *                  property="nickName",
     *                  type="string",
     *                  description="昵称",
     *              ),
     *              @SWG\Property(
     *                  property="signature",
     *                  type="string",
     *                  description="签名",
     *              ),
     *              @SWG\Property(
     *                  property="gender",
     *                  type="integer",
     *                  description="性别  1:男 2：女",
     *              ),
     *              @SWG\Property(
     *                  property="avatarUrl",
     *                  type="string",
     *                  description="头像地址",
     *              ),
     *          ),
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="返回结果",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *             @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="token", type="string", description="登录token"),
     *                  @SWG\Property(property="expires_in", type="string", description="过期时间"),
     *                  @SWG\Property(property="token_type", type="string", description="token类型"),
     *             ),
     *         )
     *     )
     * )
     */
    public function wxStore(UserStore $request) {
        $validated = $request->validated();
        $service = new WeixinService();
        $openid  = $service->getOpenId($request->post('code'));
        
        if (!$openid) {
            return $this->responseFailed($service->getErrorMessage());
        }
        
        $user = User::where('open_id', $openid)->first();
    
        if (!$user) {
            try {
                $user = User::create([
                    'auth_type' => User::AUTH_TYPE_WECHAT,
                    'open_id'   => $openid,
                    'nickname'  => $validated['nickName'],
                    'gender'    => $validated['gender'],
                    'avatar'    => $validated['avatarUrl'],
                    'introduction' => $validated['signature'],
                ]);
            } catch (\Exception $e) {
                logPlus('user generate form wechat error', ['errorMessage' => $e->getMessage()], 'wechat');
                
                return $this->responseError('登录失败');
            }
        }
    
        return $this->responseData([
            'token' => auth('api')->login($user),
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'token_type' => 'Bearer',
        ]);
    }
}