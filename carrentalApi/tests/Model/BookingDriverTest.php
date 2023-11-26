<?php

namespace Tests\Model;

use App\Booking;
use App\Driver;
use Tests\TestCase;

class BookingDriverTest extends TestCase
{
    protected $truncate = [
        'drivers',
        'bookings',
        'stations',
        'locations',
        'vehicles',
        'types',
        'users',
        'user_roles',
    ];

    public function test_addDriver()
    {
        $booking  = factory(Booking::class)->state('with_stations')->create();;
        $driver_1 = factory(Driver::class)->create();
        $driver_2 = factory(Driver::class)->create();
        $driver_3 = factory(Driver::class)->create();

        $booking->addDriver($driver_1);
        $booking->addDriver($driver_2);
        $booking->addDriver($driver_3);

        $expected = [
            $driver_1->id,
            $driver_2->id,
            $driver_3->id,
        ];

        $result = array_map(function ($item) {
            return (int)$item['id'];
        }, $booking->drivers->toArray());

        $this->assertEquals($expected, $result);
    }

    public function test_addDriver_doesnt_add_same_driver_twice()
    {
        $booking  = factory(Booking::class)->state('with_stations')->create();;
        $driver_1 = factory(Driver::class)->create();
        $driver_2 = factory(Driver::class)->create();

        $booking->addDriver($driver_1);
        $booking->addDriver($driver_1);
        $booking->addDriver($driver_2);
        $booking->addDriver($driver_2);
        $booking->addDriver($driver_1);
        $booking->addDriver($driver_2);

        $expected = [
            $driver_1->id,
            $driver_2->id,
        ];

        $result = array_map(function ($item) {
            return (int)$item['id'];
        }, $booking->drivers->toArray());

        $this->assertEquals($expected, $result);
    }

}
