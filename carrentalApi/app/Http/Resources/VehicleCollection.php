<?php

namespace App\Http\Resources;

use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VehicleCollection extends ResourceCollection
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
            $normalized = (new VehicleResource($item))->toArray($request);
            //$normalized['type'] = new TypeResource($item->type);
            $normalized['station'] = new StationResource($item->station);
            $normalized['KTEO'] = $item->getKteoAttribute();
            $normalized['insurance'] = $item->getInsuranceAttribute();

            if ($request->date) {
                $normalized['reservation_status'] = $item->get_vehicle_status($request->date);
            }
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = Vehicle::query()->count('id');
            return $normalized;
        });

   }
}
