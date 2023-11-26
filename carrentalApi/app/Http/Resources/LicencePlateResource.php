<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LicencePlateResource extends JsonResource
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
            'vehicle_id' => $this->vehicle_id,
            'licence_plate' => $this->licence_plate,
            'registration_date' => $this->registration_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'documents' => DocumentResource::collection($this->documents),
        ];
    }
}