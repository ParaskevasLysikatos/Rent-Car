<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactCollection extends ResourceCollection
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
            $normalized = (new ContactResource($item))->toArray($request);

            $normalized['results'] = number_format($this->collection->count('id'));
            $normalized['g_results'] = Contact::query()->count('id');
            return $normalized;
        });
    }
}
