<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleStatusResource extends JsonResource
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
             'order' => $this->order,
              'profiles' => ProfileResource::collection($this->profiles),
        ];
    }
}