<?php

namespace App\Http\Resources;

use DB;
use Illuminate\Http\Resources\Json\JsonResource;

class Transactor extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->has('rental_id')) {
            $payment = $this->transactor->payments()->select(DB::raw('SUM(amount) as amount'))->whereHas('rentals', function ($q) use ($request){
                $q->where('rentals.id', $request['rental_id']);
            })->groupBy('payer_id')->first()->amount ?? 0;
            $balance = $this->debit - $payment;
        } else if ($request->has('booking_id')) {
            $payment = $this->transactor->payments()->select(DB::raw('SUM(amount) as amount'))->whereHas('bookings', function ($q) use ($request){
                $q->where('bookings.id', $request['booking_id']);
            })->groupBy('payer_id')->first()->amount ?? 0;
            $balance = $this->debit - $payment;
        } else {
            $balance = $this->transactor->balance?? $this->debit;
        }
        return [
            'transactor_id' => $this->transactor_id,
            'transactor_type' => $this->transactor_type,
            'name' => $this->transactor->name ?? $this->transactor->full_name ?? null,
            'balance' => $balance,
        ];
    }
}
