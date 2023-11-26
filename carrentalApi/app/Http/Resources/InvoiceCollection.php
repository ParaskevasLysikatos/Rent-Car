<?php

namespace App\Http\Resources;

use App\Invoice;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InvoiceCollection extends ResourceCollection
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
            $normalized = (new InvoiceResource($item))->toArray($request);
            // $total_invoices_amount = number_format((float)Invoice::query()->sum('final_total'),2);
            $normalized['total_amount'] = number_format((float) $this->collection->sum('final_total'), 2) ?? 0;
            $normalized['results'] = number_format($this->collection->count('id'));

            $normalized['g_results'] = Invoice::query()->count('id');
            $normalized['g_total_amount'] = number_format((float) Invoice::query()->sum('final_total'), 2) ?? 0;
            return $normalized;
        });
    }
}