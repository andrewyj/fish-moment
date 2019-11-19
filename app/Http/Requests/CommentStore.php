<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_id' => 'required|exists:posts,id',
            'comment_parent_id' => [
                'integer',
                Rule::exists('post_comments')->where(function ($query) {
                    $query->where('post_id', request()->input('post_id'))
                          ->where('id', request()->input('comment_parent_id'));
                }),
            ],
            'content' => 'required|string',
        ];
    }
}
