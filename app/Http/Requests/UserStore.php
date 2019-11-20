<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserStore extends FormRequest
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
        $genders = implode(',', array_keys(User::genderMaps()));
        return [
            'code'        => 'required|string',
            'gender'      => "in:$genders",
            'nickName'    => 'string',
            'signature'   => 'string',
            'avatarUrl'   => 'url',
        ];
    }
}
