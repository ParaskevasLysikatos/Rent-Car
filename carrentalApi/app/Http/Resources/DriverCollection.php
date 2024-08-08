<?php

namespace App\Http\Resources;

use App\Models\Driver;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DriverCollection extends ResourceCollection
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
            $normalized = (new DriverResource($item))->toArray($request);
            unset($normalized['contact_id']);
            // $normalized['contact'] = new ContactResource($item->contact);
            // if ($request->date) {
            //     $normalized['reservation_status'] = $item->get_vehicle_status($request->date);
            // }
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = Driver::query()->count('id');
            return $normalized;
        });
    }
}
