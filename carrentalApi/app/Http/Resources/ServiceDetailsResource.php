<?php

namespace App\Http\Resources;

use App\Models\LicencePlate;
use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceDetailsResource extends JsonResource
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

            'title' => $this->title,

            'order' => $this->order,

            'category' => $this->category,


        ];
    }
}
