<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class PostCommentService
{
    /**
     * 获取评论树
     * @param null $parentId
     * @param Collection $allComments
     * @return mixed
     */
    public static function getCommentTree(Collection $allComments, $parentId = null)
    {
        return $allComments
            ->where('parent_id', $parentId)
            ->map(function ($comment) use ($allComments) {
                $data = [
                    'id'             => $comment->id,
                    'nickname'       => $comment->user->nickname,
                    'content'        => $comment->content,
                    'like_count'     => $comment->like_count,
                ];
                
                // 如果当前类目不是父类目，则直接返回
                if (!$allComments->where('parent_id', $comment->id)->first()) {
                    return $data;
                }
                
                // 否则递归调用本方法，将返回值放入 children 字段中
                $data['children'] = array_values(self::getCommentTree($allComments, $comment->id));
                
                return $data;
            })->toArray();
    }


}