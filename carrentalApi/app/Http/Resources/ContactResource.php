<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ContactResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'zip' => $this->zip,
            'city' => $this->city,
            'country' => $this->country,
            'birthday' => $this->birthday,
           // 'how_old' => Carbon::parse($this->birthday)->age,
            'identification_number' => $this->identification_number,
            'identification_country' => $this->identification_country,
            'identification_created' => $this->identification_created,
            'identification_expire' => $this->identification_expire,
            'afm' => $this->afm,
            'mobile' => $this->mobile,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'birth_place'=> $this->birth_place,
        ];
    }
}