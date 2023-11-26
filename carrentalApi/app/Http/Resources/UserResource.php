<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'driver_id' => new DriverResource($this->driver) ?? $this->driver_id ,//$this->driver_id,
            'station_id' => new StationResource($this->station) ?? $this->station_id ,//$this->station_id,
            'station'=> new StationResource($this->station) ?? null,
            'role_id' => $this->role->id,
        ];
    }
}