<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Agent;
use App\Booking;
use App\BookingSource;
use App\Brand;
use App\ContactInformation;
use App\Document;
use App\Location;
use App\Station;
use App\User;
use App\Vehicle;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(Booking::class, function (Faker $faker) {

    $duration          = rand(2, 30);
    $rate              = rand(10, 50);
    $checkout_datetime = $faker->dateTimeThisMonth;
    $checkin_datetime  = (clone $checkout_datetime)->modify($duration . ' days');

    $transport_fee  = rand(0, 5) * 10;
    $insurance_fee  = rand(0, 5) * 10;
    $options_fee    = rand(0, 5) * 10;
    $fuel_fee       = rand(0, 5) * 10;
    $subcharges_fee = rand(0, 5) * 10;
    $vat            = 24 / 100;
    $discount       = rand(0, 2) / 10;
    $voucher        = rand(0, 5) * 5;
    $total_net      = Booking::calculateNetTotal($duration, $rate, $discount, $voucher, $transport_fee, $insurance_fee, $options_fee, $fuel_fee, $subcharges_fee);

    return [
        'status'            => Arr::random(Booking::getValidStatuses()),
        'duration'          => $duration,
        'rate'              => $rate,
        'checkout_datetime' => $checkout_datetime->format('Y-m-d H:i:s'),
        'checkin_datetime'  => $checkin_datetime->format('Y-m-d H:i:s'),
        'transport_fee'     => $transport_fee,
        'insurance_fee'     => $insurance_fee,
        'options_fee'       => $options_fee,
        'fuel_fee'          => $fuel_fee,
        'subcharges_fee'    => $subcharges_fee,
        'vat'               => $vat,
        'discount'          => $discount,
        'voucher'           => $voucher,
        'total_net'         => $total_net,
    ];
});

$factory->afterMakingState(Booking::class, 'with_user', function ($booking, $faker) {
    $booking->user_id = factory(User::class)->state('with_role')->create()->id;
});

$factory->afterMakingState(Booking::class, 'with_contact_information', function ($booking, $faker) {
    $booking->contact_information_id = factory(ContactInformation::class)->create()->id;
});

$factory->afterMakingState(Booking::class, 'with_vehicle', function ($booking, $faker) {
    $vehicle = factory(Vehicle::class)->state('with_type')->create();

    $booking->type_id    = $vehicle->type_id;
    $booking->vehicle_id = $vehicle->id;
});

$factory->afterMakingState(Booking::class, 'with_brand', function ($booking, $faker) {
    $booking->brand_id = factory(Brand::class)->state('with_profiles')->create()->id;;
});

$factory->afterMakingState(Booking::class, 'with_source', function ($booking, $faker) {
    $booking->source_id = factory(BookingSource::class)->create()->id;;
});

$factory->afterMakingState(Booking::class, 'with_agent', function ($booking, $faker) {
    $booking->agent_id = factory(Agent::class)->state('with_contact_information')->create()->id;;
});

$factory->afterMakingState(Booking::class, 'with_stations', function ($booking, $faker) {
    $location = factory(Location::class)->state('with_profiles')->create();

    $booking->checkout_station_id = factory(Station::class)->state('with_profiles')->create(['location_id' => $location->id])->id;
    $booking->checkin_station_id  = factory(Station::class)->state('with_profiles')->create(['location_id' => $location->id])->id;
});


$factory->afterCreatingState(Booking::class, 'with_documents', function ($booking, $faker) {
    $documents = factory(Document::class, 2)->create();

    foreach ($documents as $item) {
        $booking->addDocument($item);
    }
});
