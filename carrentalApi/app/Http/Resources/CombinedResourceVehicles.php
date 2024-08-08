<?php

namespace App\Http\Resources;

use App\Models\Station;
use App\Models\Type;
use App\Models\ClassTypes;
use App\Models\FuelTypes;
use App\Models\OwnershipTypes;
use App\Models\UseTypes;
use App\Models\PeriodicFeeType;
use App\Models\TransmissionTypes;
use App\Models\DriveTypes;
use App\Models\ColorTypes;
use App\Models\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourceVehicles extends JsonResource
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
            // general models
            'stations' => StationResource::collection(Station::all()),
            'groups' => TypeResource::collection(Type::all()),
            //vehicle--------------------
            'class' => ClassTypeResource::collection(ClassTypes::all()),
            'fuel' => FuelTypeResource::collection(FuelTypes::all()),
            'ownership' => OwnershipResource::collection(OwnershipTypes::all()),
            'use' => UseTypeResource::collection(UseTypes::all()),
            'periodicFee_types' => PeriodicFeeTypeResource::collection(PeriodicFeeType::all()),
            'transmission' => TransmissionTypeResource::collection(TransmissionTypes::all()),
            'drive_type' => DriveTypeResource::collection(DriveTypes::all()),
            'color_type' => ColorTypeResource::collection(ColorTypes::all()),
            'vehicleStatus' => VehicleStatusResource::collection(Status::all()),
        ];
    }
}
