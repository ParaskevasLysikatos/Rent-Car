<?php

namespace App\Http\Resources;

use App\BookingSource;
use App\Brand;
use App\Place;
use App\Station;
use App\Tag;
use App\Type;
use App\CancelReason;
use App\ClassTypes;
use App\FuelTypes;
use App\OwnershipTypes;
use App\UseTypes;
use App\PeriodicFeeType;
use App\TransmissionTypes;
use App\DriveTypes;
use App\Payment;
use App\Program;
use App\CompanyPreferences;
use App\Driver;
use App\Company;
use App\ColorTypes;
use App\Status;
use App\Agent;
use App\UserRole;
use App\Characteristic;
use App\Category;
use App\Option;
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
