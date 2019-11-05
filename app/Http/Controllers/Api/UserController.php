<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\School;
use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    
    /**
     * @SWG\Get(
     *      path="/user",
     *      tags={"user"},
     *      summary="用户信息",
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
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="用户id"),
     *                  @SWG\Property(property="name", type="string", description="用户名"),
     *                  @SWG\Property(property="phone", type="string", description="电话"),
     *                  @SWG\Property(property="school_id", type="integer", description="学校id"),
     *                  @SWG\Property(property="gender", type="integer", description="性别 1：男 2：女"),
     *                  @SWG\Property(property="introduction", type="string", description="简介"),
     *                  @SWG\Property(property="age", type="integer", description="年龄"),
     *                  @SWG\Property(property="photos", type="array",
     *                      @SWG\Items(type="string"),
     *                  ),
     *                  @SWG\Property(property="integral", type="integer", description="积分数"),
     *                  @SWG\Property(property="identifier", type="string", description="身份标识符"),
     *                  @SWG\Property(property="invitation_count", type="integer", description="邀请用户数"),
     *                  @SWG\Property(property="follow", type="integer", description="关注人数"),
     *                  @SWG\Property(property="follower", type="integer", description="被关注人数"),
     *                  @SWG\Property(property="nickname", type="string", description="用户昵称"),
     *                  @SWG\Property(property="avatar", type="string", description="头像"),
     *                  @SWG\Property(property="created_at", type="string", description="创建时间"),
     *                  @SWG\Property(property="updated_at", type="string", description="更新时间"),
     *                  @SWG\Property(property="school", type="object",
     *                      @SWG\Property(property="id", type="integer", description="学校id"),
     *                      @SWG\Property(property="name", type="string", description="学校名称"),
     *                      @SWG\Property(property="initial", type="string", description="首字母"),
     *                  ),
     *             ),
     *          )
     *      ),
     * )
     */
    public function user() {
        $userModel = Auth::guard()->user();
        $user = new User($userModel);
        $user['school'] = new School($userModel->school);
        
        return $this->responseData($user);
    }
    
    /**
     * @SWG\Post(
     *     path="/login",
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
    
    /**
     * @SWG\Post(
     *     path="/logout",
     *     summary="退出登录",
     *     tags={"user"},
     *     description="退出登录",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="返回结果",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *         )
     *     )
     * )
     */
    public function logout()
    {
        Auth::guard()->logout();
        
        return $this->responseSuccess('Successfully logged out');
    }

}
