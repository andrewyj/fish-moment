<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\SocialPoolCollection;
use App\Models\SocialPool;
use App\Models\UserSocialPool;
use Illuminate\Support\Facades\Validator;

class SocialPoolController extends BaseController
{
    
    public function socialPools() {
        return SocialPoolCollection::collection(SocialPool::paginate());
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
}
