<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleExchangeResource extends JsonResource
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
            'driver_id' => new DriverResource($this->driver) ?? $this->driver_id,

            'new_vehicle_type_id' => new TypeResource($this->new_vehicle_type) ?? $this->new_vehicle_type_id,
            'new_vehicle_id' => new VehicleResource($this->new_vehicle) ?? $this->new_vehicle_id,
            'new_vehicle_transition_id' => $this->new_vehicle_transition_id,
            'new_vehicle_rental_co_km' => $this->new_vehicle_rental_co_km,
            'new_vehicle_rental_co_fuel_level' => $this->new_vehicle_rental_co_fuel_level,

            'old_vehicle_id' => new VehicleResource($this->old_vehicle) ?? $this->old_vehicle_id,
            'old_vehicle_transition_id' => $this->old_vehicle_transition_id,
            'old_vehicle_rental_co_km' => $this->old_vehicle_rental_co_km,
            'old_vehicle_rental_co_fuel_level' => $this->old_vehicle_rental_co_fuel_level,
            'old_vehicle_rental_ci_km' => $this->old_vehicle_rental_ci_km,
            'old_vehicle_rental_ci_fuel_level' => $this->old_vehicle_ci_fuel_level,

            'proximate_datetime' => $this->proximate_datetime,
            'station_id' => new StationResource($this->station) ?? $this->station_id,
            'rental_id' => $this->rental ??  $this->rental_id,
            'datetime' => $this->datetime,
            'place_id' => $this->place_id,
            'place_text' => $this->place_text,
            'reason' => $this->reason,
            'status' => $this->status,
            'type' => $this->type,

            'old_vehicle_source'=> new VehicleResource($this->old_vehicle),
            'new_vehicle_source' => new VehicleResource($this->new_vehicle),

            'old_vehicle_km_diff' =>$this->getOldVehicleKmAttribute(),
            'new_vehicle_km_diff' => $this->getNewVehicleKmAttribute(),

            'rental' => $this->rental,


            'place' => ['id' => $this->place_id, 'name' => $this->place_text],


            'new_vehicle_transition'=>new TransitionResource($this->new_vehicle_transition) ?? null,
            'old_vehicle_transition' => new TransitionResource($this->old_vehicle_transition) ?? null,

            'documents' => DocumentResource::collection($this->documents),

        ];
    }
}