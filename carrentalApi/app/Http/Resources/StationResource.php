<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
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
            'code' => $this->code,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'zip_code' => $this->zip_code,
            'phone' => $this->phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'slug' => $this->slug,
            'location_id' => new LocationResource($this->location) ?? $this->location->id , // $this->location->id
            'places'=>PlaceResource::collection($this->places),
            'profiles' => ProfileResource::collection($this->profiles),
            'IamStation'=>'',
        ];
    }
}