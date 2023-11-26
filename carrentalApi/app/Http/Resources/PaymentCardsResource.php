<?php

namespace App\Http\Resources;

use App\Payment;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCardsResource extends JsonResource
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
            'id' => $this->resource,
            'title' => Payment::CARD_TYPES[$this->resource]
        ];
    }
}
