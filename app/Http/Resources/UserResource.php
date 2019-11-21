<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'username'         => $this->username,
            'phone'            => $this->phone,
            'school_id'        => $this->school_id,
            'nickname'         => $this->nickname,
            'gender'           => $this->gender,
            'avatar'           => $this->avatar,
            'introduction'     => $this->introduction,
            'age'              => $this->age,
            'photos'           => $this->photos,
            'integral'         => $this->integral,
            'identifier'       => $this->identifier,
            'invitation_count' => $this->invitation_count,
            'following_count'  => $this->following_count,
            'follower_count'   => $this->follower_count,
            'invitation_code'  => $this->invitation_code,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
