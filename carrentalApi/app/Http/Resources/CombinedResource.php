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
use App\Rental;
use App\User;
use App\Vehicle;
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResource extends JsonResource
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
            'sources'=>SourceResource::collection(BookingSource::all()),
            'brands'=>BrandResource::collection(Brand::all()),
            'tags'=>TagResource::collection(Tag::all()),
            'stations' => StationResource::collection(Station::all()),
            'places' => PlaceResource::collection(Place::all()),
            'groups'=>TypeResource::collection(Type::all()),
            'reason'=> CancelReasonResource::collection(CancelReason::all()),
            'drivers'=>DriverResource::collection(Driver::all()),
            'driversEmp' => DriverEmpResource::collection(Driver::all()->where('role', '=', 'customer')),
            'company'=>CompanyResource::collection(Company::all()),
            'agent'=>AgentResource::collection(Agent::all()),
            'sub_accounts'=>SubAccountResource::collection(Agent::all()),
            'users'=>UserResource::collection(User::all()),
            'vehicles'=>VehicleResource::collection(Vehicle::all()),

            'roles'=>RolesResource::collection(UserRole::all()),
            'characteristics'=>CharacteristicsResource::collection(Characteristic::all()),
            'categories'=>CategoryResource::collection(Category::all()),
            'options'=>OptionResource::collection(Option::all()),

            'rentals'=>RentalResource::collection(Rental::all()),//limit not forget
            'customers'=>CustomerResource::collection(Driver::all()),
            'payments'=>PaymentResource::collection(Payment::all()),

            //vehicle--------------------
            'class'=> ClassTypeResource::collection(ClassTypes::all()),
            'fuel'=> FuelTypeResource::collection(FuelTypes::all()),
            'ownership'=> OwnershipResource::collection(OwnershipTypes::all()),
            'use'=> UseTypeResource::collection(UseTypes::all()),
            'periodicFee_types'=> PeriodicFeeTypeResource::collection(PeriodicFeeType::all()),
            'transmission'=> TransmissionTypeResource::collection(TransmissionTypes::all()),
            'drive_type'=> DriveTypeResource::collection(DriveTypes::all()),
            'color_type'=>ColorTypeResource::collection(ColorTypes::all()),
            'vehicleStatus'=> VehicleStatusResource::collection(Status::all()),
            //payments--------------------
            'getMethods'=>PaymentMethodResource::collection(Payment::PAYMENTS_METHODS),
            'getCards' => PaymentCardsResource::collection(array_keys(Payment::CARD_TYPES)),
            // programs--------------------
            'programs'=> ProgramResource::collection(Program::all()),
            // company preferences
            'companyPref'=> CompanyPreferencesResource::collection(CompanyPreferences::latest()->get()),
        ];
    }
}