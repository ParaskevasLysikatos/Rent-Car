<?php

declare(strict_types=1);

namespace Tests\Search;

use App\Booking;
use App\Rental;
use App\Transition;
use App\Vehicle;
use DateTime;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchTestCase extends TestCase
{
    public static function getVehicle(array $properties = []): Vehicle
    {
        if (!isset($properties['type_id'])) {
            $properties['type_id'] = DB::table('types')->inRandomOrder()->first('id')->id;
        }

        return factory(Vehicle::class)->create($properties);
    }

    public static function getRental(
        Vehicle $vehicle,
        string $status,
        DateTime $checkout_date,
        DateTime $checkin_date
    ): Rental {
        $brand   = DB::table('brands')->inRandomOrder()->first('id');
        $user    = DB::table('users')->inRandomOrder()->first('id');
        $station = DB::table('stations')->inRandomOrder()->first('id');
        $driver  = DB::table('drivers')->inRandomOrder()->first('id');
        $source  = DB::table('booking_sources')->inRandomOrder()->first('id');

        return factory(Rental::class)
            ->create(
                [
                    'status'              => $status,
                    'user_id'             => $user->id,
                    'driver_id'           => $driver->id,
                    'vehicle_id'          => $vehicle->id,
                    'type_id'             => $vehicle->type_id,
                    'brand_id'            => $brand->id,
                    'source_id'           => $source->id,
                    'checkout_station_id' => $station->id,
                    'checkout_datetime'   => $checkout_date,
                    'checkin_station_id'  => $station->id,
                    'checkin_datetime'    => $checkin_date,
                ]
            );
    }

    public static function getBooking(
        Vehicle $vehicle,
        string $status,
        DateTime $checkout_date,
        DateTime $checkin_date
    ): Booking {
        $brand   = DB::table('brands')->inRandomOrder()->first('id');
        $user    = DB::table('users')->inRandomOrder()->first('id');
        $station = DB::table('stations')->inRandomOrder()->first('id');
        $driver  = DB::table('drivers')->inRandomOrder()->first('id');
        $source  = DB::table('booking_sources')->inRandomOrder()->first('id');

        return factory(Booking::class)
            ->create(
                [
                    'status'              => $status,
                    'user_id'             => $user->id,
                    'customer_id'         => $driver->id,
                    'vehicle_id'          => $vehicle->id,
                    'type_id'             => $vehicle->type_id,
                    'brand_id'            => $brand->id,
                    'source_id'           => $source->id,
                    'checkout_station_id' => $station->id,
                    'checkout_datetime'   => $checkout_date,
                    'checkin_station_id'  => $station->id,
                    'checkin_datetime'    => $checkin_date,
                ]
            );
    }

    public static function getTransition(
        Vehicle $vehicle,
        DateTime $checkout_date,
        DateTime $checkin_date
    ): Transition {
        $user    = DB::table('users')->inRandomOrder()->first('id');
        $station = DB::table('stations')->inRandomOrder()->first('id');
        $driver  = DB::table('drivers')->inRandomOrder()->first('id');
        $type    = DB::table('transition_types')->inRandomOrder()->first('id');

        return factory(Transition::class)
            ->create(
                [
                    'vehicle_id'    => $vehicle->id,
                    'type_id'       => $type->id,
                    'driver_id'     => $driver->id,
                    'ci_user_id'    => $user->id,
                    'ci_datetime'   => $checkin_date,
                    'ci_station_id' => $station->id,
                    'ci_place_id'   => $station->id,
                    'co_user_id'    => $user->id,
                    'co_datetime'   => $checkout_date,
                    'co_station_id' => $station->id,
                    'co_place_id'   => $station->id,
                ]
            );
    }

    public function setUp(): void
    {
        parent::setUp();

        Vehicle::query()->delete();
        Booking::query()->delete();
    }

    public function tearDown(): void
    {
        Vehicle::query()->delete();
        Booking::query()->delete();

        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}