<?php

namespace App\Http\Resources;

use DB;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactorResource extends JsonResource
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
        return [
            'id' => $this->transactor_id,
            'type' => CustomerResource::$type[$this->transactor_type],
            'name' => $this->transactor->name ?? $this->transactor->full_name ?? null
        ];
    }
}
