<?php

namespace App\Http\Resources;

use App\Characteristic;
use Illuminate\Http\Resources\Json\ResourceCollection;
use DB;

class CharacteristicsCollection extends ResourceCollection
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
            $normalized = (new CharacteristicsResource($item))->toArray($request);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = Characteristic::query()->count('id');
            return $normalized;
        });
    }
}
