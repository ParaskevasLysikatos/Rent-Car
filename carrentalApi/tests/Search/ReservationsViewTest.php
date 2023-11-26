<?php

declare(strict_types=1);

namespace Tests\Search;

use App\VehicleReservations;
use App\VehicleStatus;
use DateTime;

class ReservationsViewTest extends SearchTestCase
{
    public function test_vehicle_with_a_future_rental(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (new DateTime())->modify('+3 week');

        foreach (
            [
                'pending',
                'booking',
                'rental',
                'active',
            ] as $status
        ) {
            /** without any rentals vehicle shouldn't appear in view  */
            self::assertEquals(0, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

            $rental = self::getRental($vehicle, $status, $checkout_date, $checkin_date);

            /** without at least one valid rental vehicle should appear in view  */
            self::assertEquals(1, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

            $rental->delete();

            /** rental is soft deleted, vehicle should be removed from view  */
            self::assertEquals(0, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

            VehicleStatus::where('rental_id', $rental->id)->forceDelete();
            $rental->forceDelete();
        }

        foreach (
            [
                'completed',
                'cancelled',
            ] as $status
        ) {
            /** without any rentals vehicle shouldn't appear in view  */
            self::assertEquals(0, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

            $rental = self::getRental($vehicle, $status, $checkout_date, $checkin_date);

            /** there's a future rental, but with invalid status  */
            self::assertEquals(0, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

            VehicleStatus::where('rental_id', $rental->id)->forceDelete();
            $rental->forceDelete();
        }

        $vehicle->forceDelete();
    }

    public function test_vehicle_with_a_future_transition(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (clone $checkout_date)->modify('+1 week');

        /** without any transitions vehicle shouldn't appear in view  */
        self::assertEquals(0, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

        $transition = self::getTransition($vehicle, $checkout_date, $checkin_date);

        /** without at least one valid transition vehicle should appear in view  */
        self::assertEquals(1, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

        $transition->delete();

        /** transition is soft deleted, vehicle should be removed from view  */
        self::assertEquals(0, VehicleReservations::where('vehicle_id', $vehicle->id)->count());

        $transition->forceDelete();

        $vehicle->forceDelete();
    }

}
