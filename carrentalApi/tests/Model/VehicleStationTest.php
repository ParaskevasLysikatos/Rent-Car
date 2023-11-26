<?php


namespace Tests\Model;

use App\Booking;
use App\Transition;
use App\User;
use App\Vehicle;
use Tests\TestCase;

class VehicleStationTest extends TestCase
{
    protected $truncate = [
        'vehicle_statuses',
        'bookings',
        'transitions',
        'maintenances',
        'vehicle_statuses',
        'vehicles',
        'users',
        'user_roles',
        'stations',
        'locations',
    ];

    public function testVehicleStationIdAfterBookingCompletion()
    {
        $user    = factory(User::class)->state('with_role')->create();
        $vehicle = factory(Vehicle::class)->state('with_type')->create();

        /** @var Booking $booking */
        $booking = factory(Booking::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);

        $this->assertNull($vehicle->station_id);

        $booking->complete();
        $vehicle->refresh();

        $this->assertEquals($booking->checkin_station_id, $vehicle->station_id);
    }

    public function testVehicleStationIdAfterTransitionCompletion()
    {
        $user    = factory(User::class)->state('with_role')->create();
        $vehicle = factory(Vehicle::class)->state('with_type')->create();

        /** @var Transition $transition */
        $transition = factory(Transition::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);

        $this->assertNull($vehicle->station_id);

        $transition->complete();
        $vehicle->refresh();

        $this->assertEquals($transition->station_id_to, $vehicle->station_id);
    }
}
