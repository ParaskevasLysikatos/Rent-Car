<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
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
            'slug' => $this->slug,
            'stations' => $this->stations->pluck('id'),
            'profiles' => ProfileResource::collection($this->profiles),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}