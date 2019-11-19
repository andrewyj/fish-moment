<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\UserCollection;
use App\Http\Resources\SchoolResource;
use App\Http\Resources\UserResource;
use \App\Models\User;
use App\Models\UserFollower;
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
        $user      = new UserResource(request()->user());
        if (request()->user()->school) {
            $user['school'] = new SchoolResource(request()->user()->school);
        }else {
            $user['school'] = [];
        }
        
        return $this->responseData($user);
    }
    
    /**
     * @SWG\Post(
     *     path="/user",
     *     summary="用户信息修改",
     *     tags={"user"},
     *     description="用户信息修改",
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
     *                  property="avatar",
     *                  type="string",
     *                  description="头像",
     *              ),
     *              @SWG\Property(
     *                  property="nickname",
     *                  type="string",
     *                  description="昵称",
     *              ),
     *              @SWG\Property(
     *                  property="introduction",
     *                  type="string",
     *                  description="简介",
     *              ),
     *              @SWG\Property(
     *                  property="gender",
     *                  type="string",
     *                  description="性别 1：男 2：女",
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
     *         )
     *     )
     * )
     */
    public function modify() {
        $user   = request()->user();
        $gender = request()->post('gender', $user->gender);
        if (!isset(User::genderMaps()[$gender])) {
            return $this->responseNotFound('性别不存在');
        }
        $user->gender = $gender;
        $user->avatar       = request()->post('avatar', $user->avatar);
        $user->introduction = request()->post('introduction', $user->introduction);
        $user->nickname     = request()->post('nickname', $user->nickname);
        $user->phone        = request()->post('phone', $user->phone);
        $user->save();
        
        return $this->responseSuccess();
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
            'phone'    => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }
    
        if (! $token = auth('api')->attempt($validator->validated())) {
            return $this->unauthorized();
        }
        
        if (auth('api')->user()->disabled == User::DISABLED_YES) {
            return $this->unauthorized('用户已被禁用');
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
    
    /**
     * @SWG\Get(
     *     path="/user/followings",
     *     summary="关注用户列表",
     *     tags={"user"},
     *     description="关注用户列表",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="结果集",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *             @SWG\Property(property="data", type="array",
     *                 @SWG\Items(type="object",
     *                     @SWG\Property(property="id", type="integer", description="用户id"),
     *                     @SWG\Property(property="name", type="string", description="用户名"),
     *                     @SWG\Property(property="phone", type="string", description="电话"),
     *                     @SWG\Property(property="school_id", type="integer", description="学校id"),
     *                     @SWG\Property(property="gender", type="integer", description="性别 1：男 2：女"),
     *                     @SWG\Property(property="introduction", type="string", description="简介"),
     *                     @SWG\Property(property="age", type="integer", description="年龄"),
     *                     @SWG\Property(property="photos", type="array",
     *                         @SWG\Items(type="string"),
     *                     ),
     *                     @SWG\Property(property="integral", type="integer", description="积分数"),
     *                     @SWG\Property(property="identifier", type="string", description="身份标识符"),
     *                     @SWG\Property(property="invitation_count", type="integer", description="邀请用户数"),
     *                     @SWG\Property(property="follow", type="integer", description="关注人数"),
     *                     @SWG\Property(property="follower", type="integer", description="被关注人数"),
     *                     @SWG\Property(property="nickname", type="string", description="用户昵称"),
     *                     @SWG\Property(property="avatar", type="string", description="头像"),
     *                     @SWG\Property(property="created_at", type="string", description="创建时间"),
     *                     @SWG\Property(property="updated_at", type="string", description="更新时间"),
     *                 ),
     *             ),
     *             @SWG\Property(property="links", type="object",
     *                @SWG\Property(property="first", type="string", description="第一页页码跳转地址"),
     *                @SWG\Property(property="last", type="string", description="最后一页页码跳转地址"),
     *                @SWG\Property(property="prev", type="string", description="前一页页码跳转地址"),
     *                @SWG\Property(property="next", type="string", description="后一页页码跳转地址"),
     *             ),
     *             @SWG\Property(property="meta", type="object",
     *                @SWG\Property(property="current_page", type="integer", description="当前页"),
     *                @SWG\Property(property="from", type="integer", description="从第几条开始"),
     *                @SWG\Property(property="to", type="integer", description="到第几条"),
     *                @SWG\Property(property="last_page", type="integer", description="最后一页页码"),
     *                @SWG\Property(property="path", type="string", description="路径"),
     *                @SWG\Property(property="total", type="integer", description="总条数"),
     *                @SWG\Property(property="per_page", type="integer", description="每页显示条数"),
     *             ),
     *         )
     *     ),
     * )
     */
    public function followings() {
        return new UserCollection(request()->user()->followings()->enabled()->paginate());
    }
    
    /**
     * @SWG\Get(
     *     path="/user/followers",
     *     summary="被关注用户列表",
     *     tags={"user"},
     *     description="被关注用户列表",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="结果集",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *             @SWG\Property(property="data", type="array",
     *                 @SWG\Items(type="object",
     *                     @SWG\Property(property="id", type="integer", description="用户id"),
     *                     @SWG\Property(property="name", type="string", description="用户名"),
     *                     @SWG\Property(property="phone", type="string", description="电话"),
     *                     @SWG\Property(property="school_id", type="integer", description="学校id"),
     *                     @SWG\Property(property="gender", type="integer", description="性别 1：男 2：女"),
     *                     @SWG\Property(property="introduction", type="string", description="简介"),
     *                     @SWG\Property(property="age", type="integer", description="年龄"),
     *                     @SWG\Property(property="photos", type="array",
     *                         @SWG\Items(type="string"),
     *                     ),
     *                     @SWG\Property(property="integral", type="integer", description="积分数"),
     *                     @SWG\Property(property="identifier", type="string", description="身份标识符"),
     *                     @SWG\Property(property="invitation_count", type="integer", description="邀请用户数"),
     *                     @SWG\Property(property="follow", type="integer", description="关注人数"),
     *                     @SWG\Property(property="follower", type="integer", description="被关注人数"),
     *                     @SWG\Property(property="nickname", type="string", description="用户昵称"),
     *                     @SWG\Property(property="avatar", type="string", description="头像"),
     *                     @SWG\Property(property="created_at", type="string", description="创建时间"),
     *                     @SWG\Property(property="updated_at", type="string", description="更新时间"),
     *                 ),
     *             ),
     *             @SWG\Property(property="links", type="object",
     *                @SWG\Property(property="first", type="string", description="第一页页码跳转地址"),
     *                @SWG\Property(property="last", type="string", description="最后一页页码跳转地址"),
     *                @SWG\Property(property="prev", type="string", description="前一页页码跳转地址"),
     *                @SWG\Property(property="next", type="string", description="后一页页码跳转地址"),
     *             ),
     *             @SWG\Property(property="meta", type="object",
     *                @SWG\Property(property="current_page", type="integer", description="当前页"),
     *                @SWG\Property(property="from", type="integer", description="从第几条开始"),
     *                @SWG\Property(property="to", type="integer", description="到第几条"),
     *                @SWG\Property(property="last_page", type="integer", description="最后一页页码"),
     *                @SWG\Property(property="path", type="string", description="路径"),
     *                @SWG\Property(property="total", type="integer", description="总条数"),
     *                @SWG\Property(property="per_page", type="integer", description="每页显示条数"),
     *             ),
     *         )
     *     ),
     * )
     */
    public function followers() {
        return new UserCollection(request()->user()->followers()->enabled()->paginate());
    }
    
    /**
     * @SWG\Post(
     *     path="/login/check",
     *     summary="登录状态检测",
     *     tags={"user"},
     *     description="登录状态检测",
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
     *             @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="is_login", type="boolean", description="是否登录"),
     *             ),
     *         )
     *     )
     * )
     */
    public function checkLogin() {
        $isLogin = auth('api')->user() ? true : false;
        
        return $this->responseData(['is_login' => $isLogin]);
    }
    
    /**
     * @SWG\Patch(
     *      path="/user/follow/{user}",
     *      tags={"user"},
     *      summary="关注用户",
     *      description="关注用户",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="user",
     *          description="用户id",
     *          required=true,
     *          type="number",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="结果集",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="integer", description="状态码"),
     *              @SWG\Property(property="message", type="string", description="状态信息"),
     *          )
     *      ),
     * )
     */
    public function follow(User $user) {
        $authUser = request()->user();
        if ($user->id == $authUser->id) {
            return $this->responseFailed('不能关注自己哦！');
        }
        if ($user->disabled == User::DISABLED_YES) {
            return $this->responseFailed('没有该用户');
        }
        if ($authUser->followings()->find($user->id)) {
            return $this->responseFailed('该用户已关注');
        }
        UserFollower::create([
            'user_id'     => $user->id,
            'follower_id' => $authUser->id,
        ]);
        $user->increment('follower_count');
        $authUser->increment('following_count');
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Delete(
     *      path="/user/follow/{user}",
     *      tags={"user"},
     *      summary="取关",
     *      description="取关",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="user",
     *          description="用户id",
     *          required=true,
     *          type="number",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="结果集",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="integer", description="状态码"),
     *              @SWG\Property(property="message", type="string", description="状态信息"),
     *          )
     *      ),
     * )
     */
    public function unFollow(User $user) {
        $authUser = request()->user();
        if ($user->disabled == User::DISABLED_YES) {
            return $this->responseFailed('没有该用户');
        }
        if (!$authUser->followings()->find($user->id)) {
            return $this->responseFailed('未关注该用户');
        }
        UserFollower::where([
            'user_id'     => $user->id,
            'follower_id' => $authUser->id,
        ])->delete();
        $user->decrement('follower_count');
        $authUser->decrement('following_count');
        
        return $this->responseSuccess();
    }
}
