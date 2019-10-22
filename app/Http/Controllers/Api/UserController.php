<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    
    /**
     * @SWG\Get(
     *      path="/user",
     *      tags={"user"},
     *      summary="user",
     *      description="user",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Response(
     *          response=200,
     *          description="结果集",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="integer", description="状态码"),
     *              @SWG\Property(property="message", type="string", description="状态信息"),
     *          @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="用户id"),
     *                  @SWG\Property(property="name", type="string", description="用户名"),
     *                  @SWG\Property(property="nickname", type="string", description="用户昵称"),
     *                  @SWG\Property(property="avatar", type="string", description="头像"),
     *                  @SWG\Property(property="phone", type="string", description="电话"),
     *                  @SWG\Property(property="created_at", type="string", description="创建时间"),
     *                  @SWG\Property(property="updated_at", type="string", description="更新时间"),
     *             ),
     *          )
     *      ),
     * )
     */
    public function user() {
        return $this->responseData(new UserResource(Auth::guard()->user()));
    }
    
    /**
     * @SWG\Post(
     *     path="/user",
     *     summary="手机，密码登录",
     *     tags={"user"},
     *     description="手机密码登录",
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
     *                  property="phone",
     *                  type="string",
     *                  description="电话",
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string",
     *                  description="密码",
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
     *                  @SWG\Property(property="token", type="string", description="token"),
     *                  @SWG\Property(property="expires_in", type="string", description="过期时间"),
     *                  @SWG\Property(property="token_type", type="string", description="token类型"),
     *             ),
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->post(),[
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }
    
        if (! $token = auth('api')->attempt($validator->validated())) {
            return $this->unauthorized();
        }
        
        return $this->responseData([
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'token_type' => 'Bearer',
        ]);
    }
    
    public function logout()
    {
        Auth::guard()->logout();
        
        return $this->responseSuccess('Successfully logged out');
    }

}
