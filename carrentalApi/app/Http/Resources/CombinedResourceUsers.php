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
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourceUsers extends JsonResource
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


            'roles' => RolesResource::collection(UserRole::all()),
            'driversEmp' => DriverEmpResource::collection(Driver::all()->where('role', '=', 'employee')->take(30)),
            'stations' => StationResource::collection(Station::all()),
            'companyPref' => CompanyPreferencesResource::collection(CompanyPreferences::latest()->get()),
        ];
    }
}
