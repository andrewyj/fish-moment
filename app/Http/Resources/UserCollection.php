<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
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
            'name'             => $this->name,
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
            'follow'           => $this->follow,
            'follower'         => $this->follower,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
