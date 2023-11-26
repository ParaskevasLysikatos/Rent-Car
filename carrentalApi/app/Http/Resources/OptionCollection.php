<?php

namespace App\Http\Resources;


use app\Option;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OptionCollection extends ResourceCollection
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
            $normalized = (new OptionResource($item))->toArray($request);

            $normalized['results_t'] = number_format($this->collection->where('option_type', 'transport')->count('id'));
            $normalized['results_i'] = number_format($this->collection->where('option_type', 'insurances')->count('id'));
            $normalized['results_e'] = number_format($this->collection->where('option_type', 'extras')->count('id'));


            $normalized['g_results_t'] = Option::query()->where('option_type', 'transport')->count('id');
            $normalized['g_results_i'] = Option::query()->where('option_type', 'insurances')->count('id');
            $normalized['g_results_e'] = Option::query()->where('option_type', 'extras')->count('id');
            return $normalized;
        });
    }
}
