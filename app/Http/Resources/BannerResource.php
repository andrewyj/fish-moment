<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'id'             => $this->id,
            'title'          => $this->title,
            'introduction'   => $this->introduction,
            'code'           => $this->code,
            'picture_url'    => $this->picture_url,
            'url'            => $this->url,
            'link_type'      => $this->link_type,
        ];
    }
}
