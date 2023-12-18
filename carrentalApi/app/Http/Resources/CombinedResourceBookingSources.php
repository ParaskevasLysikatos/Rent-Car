<?php

namespace App\Http\Resources;

use App\Models\Brand;

use App\Models\Program;
use App\Models\Agent;
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourceBookingSources extends JsonResource
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
            'brands' => BrandResource::collection(Brand::all()),
            'agent' => AgentResource::collection(Agent::all()->take(30)),
            'programs' => ProgramResource::collection(Program::all()),

        ];
    }
}
