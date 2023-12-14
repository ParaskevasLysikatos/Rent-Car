<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Quote;
class QuoteCollection extends ResourceCollection
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
            $normalized = (new QuoteResource($item))->exclude_fields(['items'])->toArray($request);
            //$normalized['sub_account'] = new SubAccountResource($item->location);
            //$normalized['driver_text'] = new DriverResource($item->location);
            //$normalized['profiles'] = new ProfileResource($item->location);
            $normalized['results'] = $this->collection->count('id');
            $normalized['total_days'] =  $this->collection->sum('duration');
            $normalized['total_amount'] = number_format((float) $this->collection->sum('total'), 2)  ?? 0;

            $normalized['g_results']= Quote::query()->count('id');
            $normalized['g_total_days'] =  Quote::query()->sum('duration');
            $normalized['g_total_amount'] = number_format((float) Quote::query()->sum('total'), 2)  ?? 0;
            return $normalized;
        });
    }
}
