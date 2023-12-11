<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $invoice_id
 * @property string $name
 * @property string|null $afm
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $email
 * @property string|null $phone
 * @property string $driver
 * @property string|null $licence_number
 * @property string|null $licence_created
 * @property string|null $licence_expire
 * @property string $rental_id
 * @property string|null $booking_id
 * @property string|null $voucher_no
 * @property string|null $checkout
 * @property string|null $checkin
 * @property string|null $days
 * @property string|null $checkout_station
 * @property string|null $vehicle
 * @property string|null $vehicle_brand
 * @property string|null $vehicle_model
 * @property string|null $checkout_km
 * @property string|null $checkin_km
 * @property string|null $checkout_fuel
 * @property string|null $checkin_fuel
 * @property string|null $rental_agent
 */
class InvoiceInstance extends Model
{
    //
}
