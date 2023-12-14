<?php

namespace App\Http\Resources;

use App\Models\LicencePlate;
use App\Models\ServiceVisit;
use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceVisitCollection extends ResourceCollection
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
            $normalized = (new ServiceVisitResource($item))->toArray($request);
            // $normalized['licence_plate'] =LicencePlate::find($item->vehicle_id);
            // $normalized['vehicle'] = Vehicle::find($item->vehicle_id);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = ServiceVisit::query()->count('id');
            return $normalized;
        });
    }
}
