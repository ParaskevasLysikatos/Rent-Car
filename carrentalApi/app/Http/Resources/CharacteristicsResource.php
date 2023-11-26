<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CharacteristicsResource extends JsonResource
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
            'icon' => $this->icon ? url('storage/' . $this->icon) : null,
            'profiles' => ProfileResource::collection($this->profiles)
        ];
    }
}
