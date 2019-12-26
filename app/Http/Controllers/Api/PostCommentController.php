<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentStore;
use App\Models\Post;
use App\Models\PostComment;
use App\Services\PostCommentService;

class PostCommentController extends BaseController
{
    /**
     * @SWG\Post(
     *      path="/post-comment",
     *      tags={"post-comment"},
     *      summary="创建推文评论",
     *      description="创建推文评论",
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
     *                  property="post_id",
     *                  type="integer",
     *                  description="推文id",
     *              ),
     *              @SWG\Property(
     *                  property="comment_parent_id",
     *                  type="integer",
     *                  description="父评论id（回复评论），选填",
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
    public function store(CommentStore $request) {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $validated['content'] = str_replace(config('filter-keywords'), '*', $validated['content']);
        PostComment::create($validated);
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Delete(
     *      path="/post-comment/{postComment}",
     *      tags={"post-comment"},
     *      summary="删除推文评论",
     *      description="删除推文评论",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="postComment",
     *          description="评论id",
     *          required=true,
     *          type="string",
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
    public function delete(PostComment $postComment) {
        if ($postComment->user_id != request()->user()->id) {
            return $this->responseFailed('只能删除自己发布的评论');
        }
        $postComment->delete();
        
        return $this->responseSuccess();
    }
    
    /**
     * @SWG\Get(
     *      path="/post-comment/{post}",
     *      tags={"post-comment"},
     *      summary="获取推文下的评论树",
     *      description="获取推文下的评论树",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *      security={{"api_key": {"scope"}}},
     *      @SWG\Parameter(
     *          in="path",
     *          name="post",
     *          description="推文id",
     *          required=true,
     *          type="string",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="结果集",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *             @SWG\Property(property="data", type="array",
     *                  @SWG\Items(type="object",
     *                      @SWG\Property(property="id", type="integer", description="评论id"),
     *                      @SWG\Property(property="nickname", type="string", description="用户昵称"),
     *                      @SWG\Property(property="content", type="string", description="评论内容"),
     *                      @SWG\Property(property="like_count", type="integer", description="喜欢次数"),
     *                          @SWG\Property(property="children", type="array",
     *                              @SWG\Items(type="object",
     *                                  @SWG\Property(property="id", type="integer", description="评论id"),
     *                                  @SWG\Property(property="nickname", type="string", description="用户昵称"),
     *                                  @SWG\Property(property="content", type="string", description="评论内容"),
     *                                  @SWG\Property(property="like_count", type="integer", description="喜欢次数"),
     *                              ),
     *                          ),
     *                      )
     *                  ),
     *              )
     *         )
     * )
     */
    public function postComments(Post $post) {
        $allComments = PostComment::where('post_id', $post->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return $this->responseData(PostCommentService::getCommentTree($allComments));
    }
}
