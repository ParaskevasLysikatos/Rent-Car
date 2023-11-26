<?php

namespace App\Http\Resources;

use App\RateCode;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RateCodesCollection extends ResourceCollection
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
            $normalized = (new RateCodesResource($item))->toArray($request);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = RateCode::query()->count('id');
            return $normalized;
        });
    }
}