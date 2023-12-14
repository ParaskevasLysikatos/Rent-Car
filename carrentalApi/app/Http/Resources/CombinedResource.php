<?php

namespace App\Http\Resources;

use App\Models\BookingSource;
use App\Models\Brand;
use App\Models\Place;
use App\Models\Station;
use App\Models\Tag;
use App\Models\Type;
use App\Models\CancelReason;
use App\Models\ClassTypes;
use App\Models\FuelTypes;
use App\Models\OwnershipTypes;
use App\Models\UseTypes;
use App\Models\PeriodicFeeType;
use App\Models\TransmissionTypes;
use App\Models\DriveTypes;
use App\Models\Payment;
use App\Models\Program;
use App\Models\CompanyPreferences;
use App\Models\Driver;
use App\Models\Company;
use App\Models\ColorTypes;
use App\Models\Status;
use App\Models\Agent;
use App\Models\UserRole;
use App\Models\Characteristic;
use App\Models\Category;
use App\Models\Option;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
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
