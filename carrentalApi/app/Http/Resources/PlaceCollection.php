<?php

namespace App\Http\Resources;

use App\Place;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PlaceCollection extends ResourceCollection
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
            $normalized = (new PlaceResource($item))->toArray($request);
            $normalized['stations'] = StationResource::collection($item->stations);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = Place::query()->count('id');
            return $normalized;
        });
    }
}
