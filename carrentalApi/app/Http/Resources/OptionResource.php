<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

class OptionResource extends JsonResource
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
           // 'cost' => $this->cost, not used
            'cost_daily' => $this->cost_daily, // this use instead
            'cost_max' => $this->cost_max,
            'items_max' => $this->items_max,
            'active_daily_cost' => $this->active_daily_cost,
            'default_on' => $this->default_on,
            'option_type' => $this->option_type,
            'order' => $this->order,
            'code' => $this->code,
            'icon' => $this->icon ? url('storage/' . $this->icon) : null,
            'profiles' => ProfileResource::collection($this->profiles),

        ];
    }
}