<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,
            'range' => $this->range,
            'date' => $this->date,
            'fpa' => $this->fpa,
            'discount' => $this->discount,
            'datePayed' => $this->datePayed,
            'notes' => $this->notes,
            'payment_way' => $this->payment_way,
            'invoicee_type' => $this->invoicee_type,
            'invoicee_id' => $this->invoicee_id,
            'sub_discount_total' => $this->sub_discount_total,
            'fpa_perc' => $this->fpa_perc,
            'final_fpa' => $this->final_fpa,
            'final_total' => $this->final_total,
            'brand_id' => new BrandResource($this->brand) ?? $this->brand_id,
            'station_id' => new StationResource($this->station) ?? $this->station_id,
            'sequence_number' => $this->sequence_number,
            'total' => $this->total,
            'rental_id' =>  $this->rental ?? $this->rental_id,
            'sent_to_aade' => $this->sent_to_aade,

            'print' => $this->getLastPrintingAttribute(),

            'instance'=>$this->instance,
            'invoicee'=>new CustomerResource($this->invoicee),
            'brand'=>new BrandResource($this->brand),
            'items'=>$this->items,
            'payment_id'=>$this->payments->pluck('id'),
            'IamInvoice' => ''
        ];
        return $array;
    }
}