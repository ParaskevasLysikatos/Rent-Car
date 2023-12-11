<?php

namespace App\Http\Resources;

use App\UserRole;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RolesCollection extends ResourceCollection
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
            $normalized = (new RolesResource($item))->toArray($request);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = UserRole::query()->count('id');
            return $normalized;
        });
    }
}
