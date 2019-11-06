<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialPoolResource extends JsonResource
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
            'name'         => $this->name,
            'user_id'      => $this->user_id,
            'school_id'    => $this->school_id,
            'avatar'       => $this->avatar,
            'description'  => $this->description,
            'introduction' => $this->introduction,
            'user_count'   => $this->user_count,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
