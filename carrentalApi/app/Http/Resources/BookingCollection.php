<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Resources\Json\ResourceCollection;
class BookingCollection extends ResourceCollection
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
            $normalized = (new BookingResource($item))->exclude_fields(['items'])->toArray($request);
            //$normalized['driver_text'] = new DriverResource($item->location);
            $normalized['results'] = $this->collection->count('id');
            $normalized['total_days'] =  $this->collection->sum('duration');
            $normalized['total_amount'] = number_format((float) $this->collection->sum('total'), 2) ?? 0;

            $normalized['g_results'] = Booking::query()->count('id');
            $normalized['g_total_days'] = Booking::query()->sum('duration');
            $normalized['g_total_amount'] = number_format((float) Booking::query()->sum('total'), 2) ?? 0;
            return $normalized;
        });
    }

}
