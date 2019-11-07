<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\SocialPoolCollection;
use App\Http\Resources\SchoolResource;
use App\Http\Resources\SocialPoolResource;
use App\Models\SocialPool;
use App\Models\UserSocialPool;
use Illuminate\Support\Facades\Validator;

class SocialPoolController extends BaseController
{
    /**
     * @SWG\Get(
     *     path="/social-pools",
     *     summary="圈子列表",
     *     tags={"social-pool"},
     *     description="圈子列表",
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
     *                     @SWG\Property(property="id", type="integer", description="圈子id"),
     *                     @SWG\Property(property="user_id", type="integer", description="创建者id"),
     *                     @SWG\Property(property="school_id", type="integer", description="学校id"),
     *                     @SWG\Property(property="avatar", type="string", description="头像"),
     *                     @SWG\Property(property="name", type="string", description="名称"),
     *                     @SWG\Property(property="description", type="string", description="描述"),
     *                     @SWG\Property(property="introduction", type="string", description="简介"),
     *                     @SWG\Property(property="user_count", type="string", description="加入人数"),
     *                     @SWG\Property(property="created_at", type="string", description="创建时间"),
     *                     @SWG\Property(property="updated_at", type="string", description="更新"),
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
    public function socialPools() {
        return new SocialPoolCollection(SocialPool::paginate());
    }
    
    /**
     * @SWG\Post(
     *      path="/social-pool/join/{socialPool}",
     *      tags={"social-pool"},
     *      summary="加入圈子",
     *      description="加入圈子",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="socialPool",
     *          description="圈子id",
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
    public function join(SocialPool $socialPool) {
        $userId = request()->user()->id;
        $socialPoolId = $socialPool->id;
        if (UserSocialPool::where([
            ['user_id', '=', $userId],
            ['social_pool_id', '=', $socialPoolId],
        ])->exists()) {
            return $this->responseFailed('用户已加入该圈子');
        }
        UserSocialPool::create([
            'user_id' => $userId,
            'social_pool_id' => $socialPoolId
        ]);
        $socialPool->increment('user_count');
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Post(
     *      path="/social-pool/quit/{socialPool}",
     *      tags={"social-pool"},
     *      summary="退出圈子",
     *      description="退出圈子",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="socialPool",
     *          description="圈子id",
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
    public function quit(SocialPool $socialPool) {
        $userId = request()->user()->id;
        $socialPoolId = $socialPool->id;
        if (!$userSocialPool = UserSocialPool::where([
            ['user_id', '=', $userId],
            ['social_pool_id', '=', $socialPoolId],
        ])->first()) {
            return $this->responseFailed('用户未加入该圈子');
        }
        $userSocialPool->delete();
        $socialPool->decrement('user_count');
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Post(
     *     path="/social-pool",
     *     summary="创建圈子",
     *     tags={"social-pool"},
     *     description="创建圈子",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="name",
     *                  type="string",
     *                  description="名称，必填",
     *              ),
     *              @SWG\Property(
     *                  property="school_id",
     *                  type="integer",
     *                  description="学校id，必填",
     *              ),
     *             @SWG\Property(
     *                  property="avatar",
     *                  type="string",
     *                  description="头像地址",
     *              ),
     *              @SWG\Property(
     *                  property="description",
     *                  type="string",
     *                  description="描述",
     *              ),
     *              @SWG\Property(
     *                  property="introduction",
     *                  type="string",
     *                  description="简介",
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
    public function store() {
        $validator = Validator::make(request()->post(),[
            'name'          => 'required|string',
            'school_id'     => 'required|exists:schools,id',
            'avatar'        => 'string',
            'description'   => 'string',
            'introduction'  => 'string',
        ]);
    
        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }
        SocialPool::create(array_merge($validator->validated(), ['user_id' => request()->user()->id]));
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Patch(
     *     path="/social-pool/{socialPool}",
     *     summary="修改圈子",
     *     tags={"social-pool"},
     *     description="修改圈子",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *          in="path",
     *          name="socialPool",
     *          description="圈子id",
     *          required=true,
     *          type="number",
     *      ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="name",
     *                  type="string",
     *                  description="名称",
     *              ),
     *              @SWG\Property(
     *                  property="school_id",
     *                  type="integer",
     *                  description="学校id",
     *              ),
     *             @SWG\Property(
     *                  property="avatar",
     *                  type="string",
     *                  description="头像地址",
     *              ),
     *              @SWG\Property(
     *                  property="description",
     *                  type="string",
     *                  description="描述",
     *              ),
     *              @SWG\Property(
     *                  property="introduction",
     *                  type="string",
     *                  description="简介",
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
    public function update(SocialPool $socialPool) {
        if ($socialPool->user_id != request()->user()->id) {
            return $this->responseFailed('只有圈子创建人可进行修改');
        }
        $validator = Validator::make(request()->post(),[
            'name'          => 'string',
            'school_id'     => 'exists:schools,id',
            'avatar'        => 'string',
            'description'   => 'string',
            'introduction'  => 'string',
        ]);
    
        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }
        $socialPool->update($validator->validated());
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Get(
     *      path="/social-pool/{socialPool}",
     *      tags={"social-pool"},
     *      summary="圈子详情",
     *      description="圈子详情",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="socialPool",
     *          description="圈子id",
     *          required=false,
     *          type="number",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="结果集",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="integer", description="状态码"),
     *              @SWG\Property(property="message", type="string", description="状态信息"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="圈子"),
     *                  @SWG\Property(property="name", type="string", description="圈子名"),
     *                  @SWG\Property(property="user_id", type="integer", description="创建者"),
     *                  @SWG\Property(property="school_id", type="integer", description="学校id"),
     *                  @SWG\Property(property="avatar", type="string", description="头像"),
     *                  @SWG\Property(property="description", type="string", description="描述"),
     *                  @SWG\Property(property="introduction", type="string", description="简介"),
     *                  @SWG\Property(property="user_count", type="integer", description="加入用户数"),
     *                  @SWG\Property(property="created_at", type="string", description="创建时间"),
     *                  @SWG\Property(property="updated_at", type="string", description="修改时间"),
     *                  @SWG\Property(property="links", type="object",
     *                      @SWG\Property(property="id", type="integer", description="学校id"),
     *                      @SWG\Property(property="name", type="string", description="学校名称"),
     *                      @SWG\Property(property="initial", type="string", description="首字母"),
     *                  ),
     *             ),
     *          )
     *      ),
     * )
     */
    public function detail(SocialPool $socialPool) {
        $detail = (new SocialPoolResource($socialPool))->toArray(request());
        if ($socialPool->school) {
            $detail['school'] = new SchoolResource($socialPool->school);
        } else {
            $detail['school'] = [];
    
        }
        
        return $this->responseData($detail);
    }
}
