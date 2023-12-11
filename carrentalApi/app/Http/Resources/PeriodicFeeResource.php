<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PeriodicFeeResource extends JsonResource
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
            'id'=>$this->id,
            'periodic_fee_type_id'=>$this->periodic_fee_type_id,
            'vehicle_id'=>$this->vehicle_id,
            'title'=>$this->title,
            'description'=>$this->description,
            'fee'=>$this->fee,
            'date_start'=>$this->date_start,
            'date_expiration'=>$this->date_expiration,
            'date_payed'=>$this->date_payed,
            'fee_type'=>$this->fee_type,
            'documents' => DocumentResource::collection($this->documents),
        ];
    }
}
