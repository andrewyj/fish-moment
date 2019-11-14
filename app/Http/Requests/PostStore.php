<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class PostStore extends FormRequest
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
        $resourceType = implode(',', array_keys(Post::resourceTypeMaps()));
        return [
            'content' => 'required|string',
            'resource_type' => "in|$resourceType",
            'resource_urls' => 'array',
        ];
    }
}
