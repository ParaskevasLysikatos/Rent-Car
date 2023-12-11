<?php

namespace App\Http\Resources;

use DB;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactorCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item) use ($request) {
            $normalized = (new TransactorResource($item))->toArray($request);
            return $normalized;
        });
    }
}