<?php

namespace App\Http\Resources;

use App\Models\Agent;
use App\Models\Company;
use App\Models\Driver;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public static $wrap = null;
    public static $type = [
        Driver::class => 'driver',
        Agent::class => 'agent',
        Company::class => 'company'
    ];
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $model=get_class($this->resource);
        return [
            'id' => $this->id,
            'type' => self::$type[$model] ,
            'name'=> $model==Driver::class ? $this->full_name : $this->name,
            'email'=> $model==Driver::class ? $this->contact->email : ($model==Company::class ?  $this->email : '' ) ?? '',
           'emailAgent'=> $this->contact_information ? $this->contact_informationEmail() : $this->company->email ?? ''
        ];
    }
}
