<?php

namespace App\Http\Resources;

use App\Models\VehicleStatus;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VehicleStatusCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item) use ($request) {
            $normalized = (new VehicleStatusResource($item))->toArray($request);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = VehicleStatus::query()->count('id');
            return $normalized;
        });
    }
}
