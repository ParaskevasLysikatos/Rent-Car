<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
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
            'program_id' => $this->program_id,
            'brand_id' => new BrandResource($this->brand) ?? $this->brand_id,//$this->brand_id,
            'agent_id' => $this->agent_id,// error if i try resource
            'slug'=>$this->slug,
            'profiles' => ProfileResource::collection($this->profiles),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'IamSource'=>''
        ];
    }
}