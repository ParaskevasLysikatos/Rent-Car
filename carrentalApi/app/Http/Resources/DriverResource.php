<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class DriverResource extends JsonResource
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
            'full_name' => $this->full_name,
            'notes' => $this->notes,
            'licence_number' => $this->licence_number,
            'licence_country' => $this->licence_country,
            'licence_created' => $this->licence_created,
            'licence_expire' => $this->licence_expire,
            'contact_id' => $this->contact_id,

            'user' =>  $this->user() ?? null,
            'role' => $this->role,
            'documents' => DocumentResource::collection($this->documents),
            'contact'=> $this->contact_id ? new ContactResource($this->contact) : null,
            'rentals'=> $this->rentals_primary,
            'bookings' =>$this->bookings_primary,
            'quotes'=>$this->quotes_primary,
            'invoices'=>$this->invoices,

            'IamDriver'=>'',
        ];
    }
}
