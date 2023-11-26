<?php

namespace App\Http\Resources;

use App\Station;
use App\Type;
use App\ClassTypes;
use App\FuelTypes;
use App\OwnershipTypes;
use App\UseTypes;
use App\PeriodicFeeType;
use App\TransmissionTypes;
use App\DriveTypes;
use App\ColorTypes;
use App\Status;
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
