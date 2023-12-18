<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Models\Station;
use App\Models\Payment;
use App\Models\Driver;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CombinedResourcePayments extends JsonResource
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
            'customers' => CustomerResource::collection(Driver::all()->where('role', '=', 'customer')->take(30)),
            'users' => UserResource::collection(User::all()),
            'getMethods' => PaymentMethodResource::collection(Payment::PAYMENTS_METHODS),
            'getCards' => PaymentCardsResource::collection(array_keys(Payment::CARD_TYPES)),

        ];
    }
}
