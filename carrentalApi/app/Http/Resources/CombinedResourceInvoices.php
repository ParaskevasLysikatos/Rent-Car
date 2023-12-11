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
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourceInvoices extends JsonResource
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

            'stations' => StationResource::collection(Station::all()),
            'brands' => BrandResource::collection(Brand::all()),

            'rentals' => RentalResource::collection(Rental::all()->take(30)), //limit not forget
            'customers' => CustomerResource::collection(Driver::all()->where('role','=','customer')->take(30)),
            'payments' => PaymentResource::collection(Payment::all()->take(30)),

        ];
    }
}
