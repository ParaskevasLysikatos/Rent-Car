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
use App\Rental;
use App\Transaction;
use App\User;
use App\Option;
use App\Vehicle;
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