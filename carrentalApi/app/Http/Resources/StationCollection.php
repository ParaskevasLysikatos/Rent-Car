<?php

namespace App\Http\Resources;

use App\Station;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StationCollection extends ResourceCollection
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
            $normalized = (new StationResource($item))->toArray($request);
            $normalized['location'] = new LocationResource($item->location);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = Station::query()->count('id');
            return $normalized;
        });
    }
}
