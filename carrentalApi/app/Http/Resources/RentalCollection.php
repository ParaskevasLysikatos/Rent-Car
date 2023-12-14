<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Rental;
class RentalCollection extends ResourceCollection
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
            $normalized = (new RentalResource($item))->exclude_fields(['items'])->toArray($request);
            // if ($request->date) {
            //     $normalized['reservation_status'] = $item->get_vehicle_status($request->date);
            // }
            $normalized['results'] = $this->collection->count('id');
            $normalized['total_days'] =  $this->collection->sum('duration');
            $normalized['total_amount'] = number_format((float) $this->collection->sum('total'), 2)  ?? 0;

            $normalized['g_results'] = Rental::query()->count('id');
            $normalized['g_total_days'] = Rental::query()->sum('duration');
            $normalized['g_total_amount'] = number_format((float) Rental::query()->sum('total'), 2)  ?? 0;
            return $normalized;
        });
    }
}
