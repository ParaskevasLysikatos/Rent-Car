<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->profile_title,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'slug' => $this->slug,
            'profiles' => ProfileResource::collection($this->profiles)
        ];
    }
}
