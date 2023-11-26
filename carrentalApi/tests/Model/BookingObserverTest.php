<?php

namespace Tests\Model;

use App\Booking;
use App\Driver;
use App\Exceptions\InvalidDatesException;
use App\Vehicle;
use Carbon\Carbon;
use Tests\TestCase;

class BookingObserverTest extends TestCase
{
    protected $truncate = [
        'bookings',
        'documents',
        'stations',
        'locations',
        'vehicles',
        'types',
        'categories',
    ];

    public function test_saving_duration()
    {
        $checkout_datetime = new Carbon();
        $checkin_datetime  = (clone $checkout_datetime)->modify('+5 days');

        $booking = factory(Booking::class)->state('with_stations')->create([
            'checkout_datetime' => $checkout_datetime,
            'checkin_datetime'  => $checkin_datetime,
        ]);

        $this->assertEquals(5, $booking->duration);

        $checkout_datetime = new Carbon();
        $checkin_datetime  = (clone $checkout_datetime)->modify(3 * 24 . ' hours');

        $booking = factory(Booking::class)->state('with_stations')->create([
            'checkout_datetime' => $checkout_datetime,
            'checkin_datetime'  => $checkin_datetime,
        ]);

        $this->assertEquals(3, $booking->duration);
    }

    public function test_incorrect_dates()
    {
        $this->expectException(InvalidDatesException::class);

        $checkout_datetime = new Carbon();
        $checkin_datetime  = (clone $checkout_datetime)->modify('-10 minutes');

        factory(Booking::class)->state('with_stations')->create([
            'checkout_datetime' => $checkout_datetime,
            'checkin_datetime'  => $checkin_datetime,
        ]);
    }

    public function test_get_type_from_vehicle()
    {
        $vehicle = factory(Vehicle::class)->state('with_type')->create();
        $booking = factory(Booking::class)->state('with_stations')->create([
            'vehicle_id' => $vehicle
        ]);

        $this->assertEquals($vehicle->type_id, $booking->type_id);
    }
}
