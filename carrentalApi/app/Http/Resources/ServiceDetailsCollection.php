<?php

namespace App\Http\Resources;

use App\Models\LicencePlate;
use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceDetailsCollection extends ResourceCollection
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
            $normalized = (new ServiceDetailsResource($item))->toArray($request);
            return $normalized;
        });
    }
}
