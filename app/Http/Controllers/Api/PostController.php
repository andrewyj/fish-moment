<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PostStore;
use App\Http\Resources\Collections\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostHeart;
use App\Models\User;

class PostController extends BaseController
{
    /**
     * @SWG\Post(
     *      path="/post",
     *      tags={"post"},
     *      summary="创建推文",
     *      description="创建推文",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="content",
     *                  type="string",
     *                  description="内容",
     *              ),
     *              @SWG\Property(
     *                  property="resource_urls",
     *                  type="array",
     *                  @SWG\Items(type="string"),
     *              ),
     *              @SWG\Property(
     *                  property="resource_type",
     *                  type="integer",
     *                  description="资源类型 0：图片 1：视频",
     *              ),
     *          ),
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
    public function store(PostStore $request) {
        $user      = $request->user();
        $validated = $request->validated();
        $validated['user_id']   = $user->id;
        $validated['school_id'] = $user->verify_status == User::VERIFY_STATUS_PASS ? $user->school_id : 0;
        Post::create($validated);
        
        return $this->responseSuccess('创建成功！审核通过后可进行查看');
    }
    
    /**
     * @SWG\Get(
     *      path="/post/{post}",
     *      tags={"post"},
     *      summary="推文详情",
     *      description="推文详情",
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="post",
     *          description="推文id",
     *          required=true,
     *          type="integer",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="结果集",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="integer", description="状态码"),
     *              @SWG\Property(property="message", type="string", description="状态信息"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="用户id"),
     *                  @SWG\Property(property="user_id", type="integer", description="用户id"),
     *                  @SWG\Property(property="school_id", type="integer", description="学校id"),
     *                  @SWG\Property(property="verify_status", type="integer", description="审核状态 0：待审核 1：审核中 2：审核通过"),
     *                  @SWG\Property(property="resource_type", type="integer", description="资源类型 0：图片 1：视频"),
     *                  @SWG\Property(property="resource_urls", type="string", description="资源链接地址数组"),
     *                  @SWG\Property(property="repost_count", type="integer", description="转发次数"),
     *                  @SWG\Property(property="like_count", type="integer", description="喜欢次数"),
     *                  @SWG\Property(property="dislike_count", type="integer", description="不喜欢次数"),
     *                  @SWG\Property(property="comment_count", type="integer", description="评论次数"),
     *                  @SWG\Property(property="content", type="string", description="内容"),
     *                  @SWG\Property(property="created_at", type="string", description="创建时间"),
     *                  @SWG\Property(property="updated_at", type="string", description="更新时间"),
     *             ),
     *          )
     *      ),
     * )
     */
    public function show(Post $post) {
        if ($post->verify_status != Post::VERIFY_STATUS_PASS) {
            return $this->responseNotFound('推文不存在');
        }
        
        return $this->responseData(new PostResource($post));
    }
    
    /**
     * @SWG\Get(
     *     path="post/mine/{status}",
     *     summary="我的帖子",
     *     tags={"post"},
     *     description="我的帖子",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         in="path",
     *         name="status",
     *         description="审核状态  0：待审核 1：审核中 2：审核通过",
     *         required=true,
     *         type="integer",
     *     ),
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
     *                     @SWG\Property(property="user_id", type="integer", description="用户id"),
     *                     @SWG\Property(property="school_id", type="integer", description="学校id"),
     *                     @SWG\Property(property="verify_status", type="integer", description="审核状态 0：待审核 1：审核中 2：审核通过"),
     *                     @SWG\Property(property="resource_type", type="integer", description="资源类型 0：图片 1：视频"),
     *                     @SWG\Property(property="resource_urls", type="string", description="资源链接地址数组"),
     *                     @SWG\Property(property="repost_count", type="integer", description="转发次数"),
     *                     @SWG\Property(property="like_count", type="integer", description="喜欢次数"),
     *                     @SWG\Property(property="dislike_count", type="integer", description="不喜欢次数"),
     *                     @SWG\Property(property="comment_count", type="integer", description="评论次数"),
     *                     @SWG\Property(property="content", type="string", description="内容"),
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
    public function mine($status) {
        if (!isset(Post::verifyStatusMaps()[$status])) {
            return $this->responseNotFound('审核状态不存在');
        }
        $posts = Post::where([
            ['user_id', '=', request()->user()->id],
            ['status', '=', $status],
        ])->get();
        
        return new PostCollection($posts);
    }
    
    /**
     * @SWG\Post(
     *      path="/post/like/{post}",
     *      tags={"post"},
     *      summary="推文喜欢",
     *      description="推文喜欢",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="post",
     *          description="推文id",
     *          required=true,
     *          type="integer",
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
    public function like(Post $post) {
        $like = PostHeart::where([
            ['user_id', '=', request()->user()->id],
            ['post_id', '=', $post->id],
            ['type', '=', PostHeart::TYPE_LIKE],
        ])->first();
        
        //取消喜欢
        if ($like) {
            $like->delete();
            $post->decrement('like_count');
            return $this->responseSuccess();
        }
        $post->increment('like_count');
        PostHeart::creat([
            'user_id' => request()->user()->id,
            'post_id' => $post->id,
            'type'    => PostHeart::TYPE_LIKE,
        ]);
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Post(
     *      path="/post/dislike/{post}",
     *      tags={"post"},
     *      summary="推文不喜欢",
     *      description="推文不喜欢",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="post",
     *          description="推文id",
     *          required=true,
     *          type="integer",
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
    public function dislike(Post $post) {
        $dislike = PostHeart::where([
            ['user_id', '=', request()->user()->id],
            ['post_id', '=', $post->id],
            ['type', '=', PostHeart::TYPE_DISLIKE],
        ])->first();
        
        //取消不喜欢
        if ($dislike) {
            $dislike->delete();
            $post->decrement('dislike_count');
            return $this->responseSuccess();
        }
        $post->increment('dislike_count');
        PostHeart::creat([
            'user_id' => request()->user()->id,
            'post_id' => $post->id,
            'type'    => PostHeart::TYPE_DISLIKE,
        ]);
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Delete(
     *      path="/post/{post}",
     *      tags={"post"},
     *      summary="删除推文",
     *      description="删除推文",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="post",
     *          description="推文id",
     *          required=true,
     *          type="integer",
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
    public function delete(Post $post) {
        if ($post->user_id != request()->user()->id) {
            return $this->responseFailed('只能删除自己发布的帖子');
        }
        $post->delete();
        
        return $this->responseSuccess();
    }
}
