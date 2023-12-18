<?php

namespace App\Http\Resources;


use App\Models\Station;
use App\Models\CompanyPreferences;
use App\Models\Driver;
use App\Models\UserRole;
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
