<?php

declare(strict_types=1);

namespace Tests\Search;

use App\VehicleReservations;
use DateTime;

class TransitionsScenariosTest extends SearchTestCase
{

    /**
     * Test availability between two transitions
     *
     * Transition Starts
     * Transition Ends
     *
     * Checkout
     * Checkin
     *
     * Transition Start
     * Transition Ends
     *
     * Vehicle should be available
     */
    public function test_between_transitions(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+3 week');
        $checkin_date  = (new DateTime())->modify('+4 week');

        $transition1_checkout_date = (new DateTime())->modify('+1 weeks');
        $transition1_checkin_date  = (new DateTime())->modify('+2 weeks');

        $transition2_checkout_date = (new DateTime())->modify('+5 weeks');
        $transition2_checkin_date  = (new DateTime())->modify('+6 weeks');

        $transition1 = self::getTransition($vehicle, $transition1_checkout_date, $transition1_checkin_date);
        $transition2 = self::getTransition($vehicle, $transition2_checkout_date, $transition2_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertNotContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();

        $transition1->forceDelete();
        $transition2->forceDelete();
    }

    /**
     * Test availability when checkin overlaps with a transition
     *
     * Checkout
     * Transition Starts
     * Checkin
     * Transition Ends
     *
     * Vehicle should NOT be available
     */
    public function test_checkin_overlaps(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (new DateTime())->modify('+3 week');

        $transition_checkout_date = (new DateTime())->modify('+2 weeks');
        $transition_checkin_date  = (new DateTime())->modify('+4 weeks');

        $transition = self::getTransition($vehicle, $transition_checkout_date, $transition_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $transition->forceDelete();
    }

    /**
     * Test availability when checkout overlaps with a transition
     *
     * Transition Starts
     * Checkout
     * Transition Ends
     * Checkin
     *
     * Vehicle should NOT be available
     */
    public function test_checkout_overlaps(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+3 week');
        $checkin_date  = (new DateTime())->modify('+5 week');

        $transition_checkout_date = (new DateTime())->modify('+2 weeks');
        $transition_checkin_date  = (new DateTime())->modify('+4 weeks');

        $transition = self::getTransition($vehicle, $transition_checkout_date, $transition_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $transition->forceDelete();
    }

    /**
     * Test availability when test dates inside a transition
     *
     * Transition Starts
     * Checkout
     * Checkin
     * Transition Ends
     *
     * Vehicle should NOT be available
     */
    public function test_both_checkin_and_checkout_overlap_1(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+2 week');
        $checkin_date  = (new DateTime())->modify('+3 week');

        $transition_checkout_date = (new DateTime())->modify('+1 weeks');
        $transition_checkin_date  = (new DateTime())->modify('+4 weeks');

        $transition = self::getTransition($vehicle, $transition_checkout_date, $transition_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $transition->forceDelete();
    }

    /**
     * Test availability when a transition inside test dates
     *
     * Checkout
     * Transition Starts
     * Transition Ends
     * Checkin
     *
     * Vehicle should NOT be available
     */
    public function test_both_checkin_and_checkout_overlap_2(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (new DateTime())->modify('+4 week');

        $transition_checkout_date = (new DateTime())->modify('+2 weeks');
        $transition_checkin_date  = (new DateTime())->modify('+3 weeks');

        $transition = self::getTransition($vehicle, $transition_checkout_date, $transition_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $transition->forceDelete();
    }

    /**
     * Test availability when test dates before transition
     *
     * Checkout
     * Checkin
     * Transition Starts
     * Transition Ends
     *
     * Vehicle should be available
     */
    public function test_dates_before_transition(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+1 week');
        $checkin_date  = (new DateTime())->modify('+2 week');

        $transition_checkout_date = (new DateTime())->modify('+3 weeks');
        $transition_checkin_date  = (new DateTime())->modify('+4 weeks');

        $transition = self::getTransition($vehicle, $transition_checkout_date, $transition_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertNotContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $transition->forceDelete();
    }

    /**
     * Test availability when test dates after transition
     *
     * Transition Starts
     * Transition Ends
     * Checkout
     * Checkin
     *
     * Vehicle should be available
     */
    public function test_dates_after_transition(): void
    {
        $vehicle = self::getVehicle();

        $checkout_date = (new DateTime())->modify('+5 week');
        $checkin_date  = (new DateTime())->modify('+6 week');

        $transition_checkout_date = (new DateTime())->modify('+3 weeks');
        $transition_checkin_date  = (new DateTime())->modify('+4 weeks');

        $transition = self::getTransition($vehicle, $transition_checkout_date, $transition_checkin_date);

        $unavailable = VehicleReservations::getReservedVehicles($checkout_date, $checkin_date);

        self::assertNotContains($vehicle->id, $unavailable);

        $vehicle->forceDelete();
        $transition->forceDelete();
    }
}
