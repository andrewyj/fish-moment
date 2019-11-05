<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserThirdAuthController extends BaseController {
    
    /**
     * @SWG\Post(
     *     path="/userThirdAuth/wx",
     *     summary="微信登录",
     *     tags={"userThirdAuth"},
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
     *             ),
     *         )
     *     )
     * )
     */
    public function wxStore(Request $request) {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        $code = $request->post('code');
        $driver = \Socialite::driver(User::AUTH_TYPE_WECHAT);
        
        try {
            $response  = $driver->getAccessTokenResponse($code);
            $token     = array_get($response, 'access_token');
            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            logPlus('wechat login error', [
                'errorMessage' => $e->getMessage(),
                'response'     => $response ?? '',
                'oauthUser'    => $oauthUser ?? '',
            ], 'wechat');
            
            return $this->unauthorized('参数错误，未获取用户信息');
        }
        
        $unionId = $oauthUser->offsetExists('unionid') ? : null;
        
        if ($unionId) {
            $user = User::where([
                ['auth_type', User::AUTH_TYPE_WECHAT],
                ['union_id', $unionId]
            ])->first();
        } else {
            $user = User::where('open_id', $oauthUser->getId())->first();
        }
        
        if (!$user) {
            
            try {
                DB::beginTransaction();
                $user = User::create([
                    'auth_type' => User::AUTH_TYPE_WECHAT,
                    'open_id'   => $oauthUser->getId(),
                    'union_id'  => $unionId,
                    'nickname'  => $oauthUser->getNickname(),
                    'avatar'    => $oauthUser->getAvatar(),
                    'user_id'   => $user->id
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                logPlus('user generate form wechat error', ['errorMessage' => $e->getMessage()], 'wechat');
                
                return $this->responseError('登录失败');
            }
            DB::commit();
        }
        
        return $this->responseData(['token' => $user->token]);
    }
}