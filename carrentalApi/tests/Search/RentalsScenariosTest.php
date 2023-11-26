<?php

declare(strict_types=1);

namespace Tests\Search;

use App\VehicleReservations;
use App\VehicleStatus;
use DateTime;

class RentalsScenariosTest extends SearchTestCase
{

    /**
     * Test availability between two bookings
     *
     * Rental Starts
     * Rental Ends
     *
     * Checkout
     * Checkin
     *
     * Rental Start
     * Rental Ends
     *
     * Vehicle should be available
     */
    public function test_between_rentals(): void
    {
        $vehicle = self::getVehicle();

        $rental1_checkout_date = (new DateTime())->modify('+1 weeks');
        $rental1_checkin_date  = (new DateTime())->modify('+2 weeks');

        $checkout_date = (new DateTime())->modify('+3 week');
        $checkin_date  = (new DateTime())->modify('+4 week');

        $rental2_checkout_date = (new DateTime())->modify('+5 weeks');
        $rental2_checkin_date  = (new DateTime())->modify('+6 weeks');

        $rental1 = self::getRental($vehicle, 'active', $rental1_checkout_date, $rental1_checkin_date);
        $rental2 = self::getRental($vehicle, 'active', $rental2_checkout_date, $rental2_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertNotContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();

        $rental1->forceDelete();
        $rental2->forceDelete();
    }

    /**
     * Test availability when checkin overlaps with a rental
     *
     * Checkout
     * Rental Starts
     * Checkin
     * Rental Ends
     *
     * Vehicle should NOT be available
     */
    public function test_checkin_overlaps(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (new DateTime())->modify('+3 week');

        $rental_checkout_date = (new DateTime())->modify('+2 weeks');
        $rental_checkin_date  = (new DateTime())->modify('+4 weeks');

        $rental = self::getRental($vehicle, 'active', $rental_checkout_date, $rental_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $rental->forceDelete();
    }

    /**
     * Test availability when checkout overlaps with a rental
     *
     * Rental Starts
     * Checkout
     * Rental Ends
     * Checkin
     *
     * Vehicle should NOT be available
     */
    public function test_checkout_overlaps(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+3 week');
        $checkin_date  = (new DateTime())->modify('+5 week');

        $rental_checkout_date = (new DateTime())->modify('+2 weeks');
        $rental_checkin_date  = (new DateTime())->modify('+4 weeks');

        $rental = self::getRental($vehicle, 'active', $rental_checkout_date, $rental_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $rental->forceDelete();
    }

    /**
     * Test availability when test dates inside a rental
     *
     * Rental Starts
     * Checkout
     * Checkin
     * Rental Ends
     *
     * Vehicle should NOT be available
     */
    public function test_both_checkin_and_checkout_overlap_1(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+2 week');
        $checkin_date  = (new DateTime())->modify('+3 week');

        $rental_checkout_date = (new DateTime())->modify('+1 weeks');
        $rental_checkin_date  = (new DateTime())->modify('+4 weeks');

        $rental = self::getRental($vehicle, 'active', $rental_checkout_date, $rental_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $rental->forceDelete();
    }

    /**
     * Test availability when a rental inside test dates
     *
     * Checkout
     * Rental Starts
     * Rental Ends
     * Checkin
     *
     * Vehicle should NOT be available
     */
    public function test_both_checkin_and_checkout_overlap_2(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (new DateTime())->modify('+4 week');

        $rental_checkout_date = (new DateTime())->modify('+2 weeks');
        $rental_checkin_date  = (new DateTime())->modify('+3 weeks');

        $rental = self::getRental($vehicle, 'active', $rental_checkout_date, $rental_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $rental->forceDelete();
    }

    /**
     * Test availability when test dates before rental
     *
     * Checkout
     * Checkin
     * Rental Starts
     * Rental Ends
     *
     * Vehicle should be available
     */
    public function test_dates_before_rental(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (new DateTime())->modify('+2 week');

        $rental_checkout_date = (new DateTime())->modify('+3 weeks');
        $rental_checkin_date  = (new DateTime())->modify('+4 weeks');

        $rental = self::getRental($vehicle, 'active', $rental_checkout_date, $rental_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertNotContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $rental->forceDelete();
    }

    /**
     * Test availability when test dates after rental
     *
     * Rental Starts
     * Rental Ends
     * Checkout
     * Checkin
     *
     * Vehicle should be available
     */
    public function test_dates_after_rental(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+5 week');
        $checkin_date  = (new DateTime())->modify('+6 week');

        $rental_checkout_date = (new DateTime())->modify('+3 weeks');
        $rental_checkin_date  = (new DateTime())->modify('+4 weeks');

        $rental = self::getRental($vehicle, 'active', $rental_checkout_date, $rental_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertNotContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();

        VehicleStatus::where('rental_id', $rental->id)->forceDelete();
        $rental->forceDelete();
    }
}
