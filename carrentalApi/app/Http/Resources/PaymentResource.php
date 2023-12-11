<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_datetime' => $this->payment_datetime,
            'payment_method' => $this->method,
            'payment_type' => $this->payment_type,
            'sequence_number' => $this->sequence_number,
            'payer' => $this->payer_id ? new CustomerResource($this->payer) : ['id' => null, 'type' => CustomerResource::$type[$this->payer_type] ?? null,'email'=> CustomerResource::$type[$this->email.$this->emailAgent] ?? null],
           // 'payer' => $this->payer_id ? new CustomerResource($this->payer) : ['id' => null, 'type' => $this->handlePayType($this->payer_type)],
            'balance' => $this->balance,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'brand_id' => new BrandResource($this->brand) ?? $this->brand_id,
            'user_id' => new UserResource($this->user) ?? $this->user_id,
            'station_id'  => new StationResource($this->station) ?? $this->station_id,
            'place' => ['id' => $this->place_id, 'name' => $this->place_text],
            'comments' => $this->comments,
            'rental_id' => $this->rentals->pluck('id')[0] ?? null,

            'documents' => DocumentResource::collection($this->documents),

            'payer_id' => $this->payer_id,
            'payer_type' => $this->payer_type,

            'print' => $this->getLastPrintingAttribute(),

            'credit_card_number' => $this->credit_card_number ?? '',
            'credit_card_month' => $this->credit_card_month ?? '',
            'credit_card_year'  => $this->credit_card_year ?? '',
            'credit_card_month_year'  => $this->credit_card_year . '-' . $this->credit_card_month ?? '',
            'cheque_number'     => $this->cheque_number ?? '',
            'cheque_due_date'   => $this->cheque_due_date ?? '',
            'bank_transfer_account' => $this->bank_transfer_account ?? '',
            'card_type'            => $this->card_type ?? '',
            'foreigner' => $this->foreigner ?? '',

            'booking' => $this->bookings->first(),

            'conInvoice' => $this->invoices->first(), //for connections
            'conRental' => $this->rentals->first(), //for connections

            'IamPayment' => ''
        ];
    }

    // public function handleType($type)
    // {
    //     if ($type == 'payment') {
    //         return $type = 'Είσπραξη';
    //     } else if ($type == 'refund') {
    //         return $type = 'Επιστροφή Χρημάτων';
    //     } else if ($type == 'pre-auth') {
    //         return  $type = 'Εγγύηση';
    //     } else if ($type == 'refund-pre-auth') {
    //         return $type = 'Επιστροφή Χρημάτων Εγγύησης';
    //     } else {
    //       return $type;
    //     }
    // }

}