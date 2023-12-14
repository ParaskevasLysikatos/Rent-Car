<?php

namespace App\Http\Resources;

use App\Models\LicencePlate;
use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceVisitResource extends JsonResource
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
            'user_id' => $this->user_id,
            'date_start' => $this->date_start,
            'vehicle_id' => $this->vehicle_id,
            'km' => $this->km,
            'fuel_level' => $this->fuel_level,
            'comments' => $this->comments,
            'visit_details'=> ServiceVisitDetailsResource::collection($this->visit_details),
           'vehicle' =>new VehicleResource($this->vehicle),

        ];
    }
}
