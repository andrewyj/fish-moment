<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\SocialPoolResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SocialPoolCollection extends ResourceCollection
{
    public $collects = SocialPoolResource::class;
    
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code'    => 0,
            'message' => 'success',
            'data'    => $this->collection
        ];
    }
}
