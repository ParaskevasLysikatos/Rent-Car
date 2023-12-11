<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceVisitDetailsResource extends JsonResource
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
            'service_visit_id' => $this->service_visit_id,
            'service_details_id' => $this->service_details_id,
            'service_status_id' => $this->service_status_id,
            'service_status' => new ServiceStatusResource($this->status),
        ];
    }
}
