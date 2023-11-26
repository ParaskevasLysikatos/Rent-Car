<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransitionResource extends JsonResource
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
            'type_id' => $this->type_id,
            'completed_at' => $this->completed_at,
            'vehicle_id' => new VehicleResource($this->vehicle) ?? $this->vehicle_id,
            'external_party' => $this->external_party,

            'co_datetime' => $this->co_datetime,
            'co_station_id' => new StationResource($this->co_station) ?? $this->co_station_id,
            'co_place_id' => $this->co_place_id,
            'co_notes' => $this->co_notes,
            'co_km' => $this->co_km,
            'co_fuel_level' => $this->co_fuel_level,
            'co_user_id' => new UserResource($this->co_user) ?? $this->co_user_id,
            'co_place_text' => $this->co_place_text,

            'ci_datetime' => $this->ci_datetime,
            'ci_station_id' => new StationResource($this->ci_station) ?? $this->ci_station_id,
            'ci_place_id' => $this->ci_place_id,
            'ci_notes' => $this->ci_notes,
            'ci_km' => $this->ci_km,
            'ci_fuel_level' => $this->ci_fuel_level,
            'ci_user_id' => new UserResource($this->ci_user) ?? $this->ci_user_id,
            'ci_place_text' => $this->ci_place_text,

            'notes' => $this->notes,
            'driver_id' => new DriverResource($this->driver) ?? $this->driver_id,
            'rental_id' => $this->rental_id,

            'co_user' => new UserResource($this->co_user),
            'ci_user' => new UserResource($this->ci_user),

            'type' => new TransitionTypeResource($this->type),

             'driver' => new DriverResource($this->driver) ?? null,

            'co_station' => new StationResource($this->co_station) ?? null,
            'ci_station' => new StationResource($this->ci_station) ?? null,

            'co_place' => new PlaceResource($this->co_place) ?? null,
           'ci_place' => new PlaceResource($this->ci_place)?? null,

            'co_user' => new UserResource($this->co_user),
            'ci_user' => new UserResource($this->ci_user),

            's_co_place' => ['id' => $this->co_place_id, 'name' => $this->co_place_text],
            's_ci_place' => ['id' => $this->ci_place_id, 'name' => $this->ci_place_text],

            'vehicle' => new VehicleResource( $this->vehicle),

            'distance' => $this->co_km-$this->ci_km,

            'status' => $this->vehicle_status()->status ?? null,

            'documents' => DocumentResource::collection($this->documents),

            'documentsCount' => count(DocumentResource::collection($this->documents)),




        ];
    }
}