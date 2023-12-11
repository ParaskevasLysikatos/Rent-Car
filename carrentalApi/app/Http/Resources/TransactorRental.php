<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactorRental extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'transaction_id' => $this->transaction_id,
            'transactor_id' => $this->transactor_id,
            'transactor_type' => $this->transactor_type,
            'name' => $this->transactor->name ?? $this->transactor->full_name ?? null,
            'debit' => $this->debit
        ];
    }
}
