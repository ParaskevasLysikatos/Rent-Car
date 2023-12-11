<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingItemResource extends JsonResource
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
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'option' => new OptionResource($this->option),
            'daily' => $this->daily_cost,
            'duration' => $this->duration,
            'quantity' => $this->quantity,
            'payer' => $this->payer,
            'cost' => $this->rate,
            'net_total' => $this->net,
            'total_cost' => $this->gross,
            'start' => $this->start,
            'end' => $this->end
        ];
    }
}
