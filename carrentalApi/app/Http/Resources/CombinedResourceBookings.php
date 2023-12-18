<?php

namespace App\Http\Resources;

use App\Models\BookingSource;
use App\Models\Brand;
use App\Models\Station;
use App\Models\Tag;
use App\Models\Type;
use App\Models\CancelReason;
use App\Models\Program;
use App\Models\CompanyPreferences;
use App\Models\Driver;
use App\Models\Company;
use App\Models\Option;
use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourceBookings extends JsonResource
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
            'tags' => TagResource::collection(Tag::all()),
            'options' => OptionResource::collection(Option::all()),
            'groups' => TypeResource::collection(Type::all()),
            'sources' => SourceResource::collection(BookingSource::all()),
            'stations' => StationResource::collection(Station::all()),
            'reason' => CancelReasonResource::collection(CancelReason::all()),
            'brands' => BrandResource::collection(Brand::all()),
            'drivers' => DriverResource::collection(Driver::all()->where('role', '=', 'customer')->take(30)),
            'company' => CompanyResource::collection(Company::all()->take(30)),
            'programs' => ProgramResource::collection(Program::all()),
            'vehicles' => VehicleResource::collection(Vehicle::all()->take(30)),
            'companyPref' => CompanyPreferencesResource::collection(CompanyPreferences::latest()->get()),
        ];
    }
}
