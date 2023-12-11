<?php

namespace App\Http\Resources;

use App\Agent;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AgentCollection extends ResourceCollection
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
            $normalized = (new AgentResource($item))->toArray($request);
            //$normalized['driver_text'] = new DriverResource($item->location);
            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = Agent::query()->count('id');
            return $normalized;
        });
    }
}