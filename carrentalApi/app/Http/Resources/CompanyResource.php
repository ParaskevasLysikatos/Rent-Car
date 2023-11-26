<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'afm' => $this->afm,
            'doy' => $this->doy,
            'country' => $this->country,
            'city' => $this->city,
            'job' => $this->job,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'title' => $this->title,
            'address' => $this->address,
            'comments' => $this->comments,
            'phone_2' => $this->phone_2,
            'zip_code' => $this->zip_code,
            'mite_number' => $this->mite_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'drivers'=>$this->drivers->pluck('id'),

            'rentals'=>$this->rentals,
            'bookings'=>$this->bookings,
            'quotes'=>$this->quotes,
            'invoices'=> $this->invoices,

            'IamCompany'=>''
        ];
    }
}
