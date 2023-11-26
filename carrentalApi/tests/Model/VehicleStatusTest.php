<?php


namespace Tests\Model;

use App\Booking;
use App\Exceptions\VehicleHasActiveStatusesException;
use App\Maintenance;
use App\Station;
use App\Transition;
use App\User;
use App\Vehicle;
use App\VehicleStatus;
use Tests\TestCase;

class VehicleStatusTest extends TestCase
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

    public function testOnlyOneActiveStatusComplete_fail()
    {
        $this->expectException(VehicleHasActiveStatusesException::class);

        $user    = factory(User::class)->state('with_role')->create();
        $vehicle = factory(Vehicle::class)->state('with_type')->create();

        $booking = factory(Booking::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);

        /**
         * This will fail because the first one wasn't completed
         */
        $booking = factory(Booking::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);
    }

    public function testBookingObserver()
    {
        $user    = factory(User::class)->state('with_role')->create();
        $vehicle = factory(Vehicle::class)->state('with_type')->create();

        /** @var Booking $booking */
        $booking = factory(Booking::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);

        /** @var \App\VehicleStatus $vehicle_status */
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals($booking->user_id, $vehicle_status->user_id);
        $this->assertEquals($booking->vehicle_id, $vehicle_status->vehicle_id);
        $this->assertEquals(VehicleStatus::STATUS_BOOKING, $vehicle_status->status);
        $this->assertEquals($booking->id, $vehicle_status->booking_id);
        $this->assertNull($vehicle_status->maintenance_id);
        $this->assertNull($vehicle_status->transition_id);

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);
    }

    public function testMaintenanceObserver()
    {
        $user    = factory(User::class)->state('with_role')->create();
        $vehicle = factory(Vehicle::class)->state('with_type')->create();

        /** @var \App\Maintenance $maintenance */
        $maintenance = factory(Maintenance::class)->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);

        /** @var \App\VehicleStatus $vehicle_status */
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals($maintenance->user_id, $vehicle_status->user_id);
        $this->assertEquals($maintenance->vehicle_id, $vehicle_status->vehicle_id);
        $this->assertEquals(VehicleStatus::STATUS_MAINTENANCE, $vehicle_status->status);
        $this->assertEquals($maintenance->id, $vehicle_status->maintenance_id);
        $this->assertNull($vehicle_status->booking_id);
        $this->assertNull($vehicle_status->transition_id);

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);
    }

    public function testTransitionObserver()
    {
        $user    = factory(User::class)->state('with_role')->create();
        $vehicle = factory(Vehicle::class)->state('with_type')->create();

        /** @var \App\Transition $transition */
        $transition = factory(Transition::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);

        /** @var \App\VehicleStatus $vehicle_status */
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals($transition->user_id, $vehicle_status->user_id);
        $this->assertEquals($transition->vehicle_id, $vehicle_status->vehicle_id);
        $this->assertEquals(VehicleStatus::STATUS_TRANSITION, $vehicle_status->status);
        $this->assertEquals($transition->id, $vehicle_status->transition_id);
        $this->assertNull($vehicle_status->booking_id);
        $this->assertNull($vehicle_status->maintenance_id);

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);
    }

    public function testMultipleStatusChanges()
    {
        $user         = factory(User::class)->state('with_role')->create();
        $vehicle      = factory(Vehicle::class)->state('with_type')->create();
        $station_from = factory(Station::class)->state('with_location')->create();
        $station_to   = factory(Station::class)->state('with_location')->create();

        // Change 1: Vehicle is booked

        $booking_1      = factory(Booking::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals(VehicleStatus::STATUS_BOOKING, $vehicle_status->status);
        $this->assertEquals($booking_1->id, $vehicle_status->booking_id);

        $this->assertEquals(1, $vehicle->vehicle_statuses()->count());

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);

        $booking_1->complete();

        $vehicle->refresh();
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertNull($vehicle_status);
        $this->assertEquals(Vehicle::STATUS_AVAILABLE, $vehicle->status);

        // Change 2: Vehicle is moved to another station
        $transition_1   = factory(Transition::class)->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id, 'station_id_from' => $station_from->id, 'station_id_to' => $station_to->id]);
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals(VehicleStatus::STATUS_TRANSITION, $vehicle_status->status);
        $this->assertEquals($transition_1->id, $vehicle_status->transition_id);

        $this->assertEquals(2, $vehicle->vehicle_statuses()->count());

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);

        $transition_1->complete();

        $vehicle->refresh();
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertNull($vehicle_status);
        $this->assertEquals(Vehicle::STATUS_AVAILABLE, $vehicle->status);


        // Change 3: Vehicle is off to the garage

        $maintenance_1  = factory(Maintenance::class)->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals(VehicleStatus::STATUS_MAINTENANCE, $vehicle_status->status);
        $this->assertEquals($maintenance_1->id, $vehicle_status->maintenance_id);

        $this->assertEquals(3, $vehicle->vehicle_statuses()->count());

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);

        $maintenance_1->complete();

        $vehicle->refresh();
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertNull($vehicle_status);
        $this->assertEquals(Vehicle::STATUS_AVAILABLE, $vehicle->status);


        // Change 4: Vehicle is moved to another station

        $transition_2   = factory(Transition::class)->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id, 'station_id_from' => $station_from->id, 'station_id_to' => $station_to->id]);
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals(VehicleStatus::STATUS_TRANSITION, $vehicle_status->status);
        $this->assertEquals($transition_2->id, $vehicle_status->transition_id);

        $this->assertEquals(4, $vehicle->vehicle_statuses()->count());

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);

        $transition_2->complete();

        $vehicle->refresh();
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertNull($vehicle_status);
        $this->assertEquals(Vehicle::STATUS_AVAILABLE, $vehicle->status);

        // Change 5: Vehicle is off to the garage

        /** @var \App\Maintenance $maintenance_2 */
        $maintenance_2  = factory(Maintenance::class)->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals(VehicleStatus::STATUS_MAINTENANCE, $vehicle_status->status);
        $this->assertEquals($maintenance_2->id, $vehicle_status->maintenance_id);

        $this->assertEquals(5, $vehicle->vehicle_statuses()->count());

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);

        $maintenance_2->complete();

        $vehicle->refresh();
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertNull($vehicle_status);
        $this->assertEquals(Vehicle::STATUS_AVAILABLE, $vehicle->status);

        // Change 6: Vehicle is booked

        $booking_2      = factory(Booking::class)->state('with_stations')->create(['user_id' => $user->id, 'vehicle_id' => $vehicle->id]);
        $vehicle_status = $vehicle->getActiveVehicleStatus();

        $this->assertEquals($booking_2->user_id, $vehicle_status->user_id);
        $this->assertEquals($booking_2->vehicle_id, $vehicle_status->vehicle_id);
        $this->assertEquals(VehicleStatus::STATUS_BOOKING, $vehicle_status->status);
        $this->assertEquals($booking_2->id, $vehicle_status->booking_id);

        $this->assertEquals(6, $vehicle->vehicle_statuses()->count());

        $vehicle->refresh();

        $this->assertEquals($vehicle->status, $vehicle_status->status);
    }
}
