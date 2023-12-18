<?php

namespace App\Http\Resources;

use App\Models\BookingSource;
use App\Models\Brand;
use App\Models\Program;
use App\Models\Company;
use App\Models\Agent;

use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourceAgents extends JsonResource
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
            'programs' => ProgramResource::collection(Program::all()),
            'sources' => SourceResource::collection(BookingSource::all()),
            'brands' => BrandResource::collection(Brand::all()),
            'company' => CompanyResource::collection(Company::all()->take(30)),
            'sub_accounts' => SubAccountResource::collection(Agent::all()->take(30)),

        ];
    }
}
