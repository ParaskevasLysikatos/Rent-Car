<?php

namespace App\Observers;

use App\Booking;
use App\Exceptions\InvalidDatesException;
use App\Vehicle;
use App\VehicleStatus;
use Carbon\Carbon;

class BookingObserver
{
    public function saving(Booking $booking)
    {
        $checkout_datetime = Carbon::parse($booking->checkout_datetime);
        $checkin_datetime  = Carbon::parse($booking->checkin_datetime);

        if ($checkin_datetime->lessThan($checkout_datetime)) {
            throw new InvalidDatesException('checkout datetime cannot be later than checking datetime.');
        }

        if (is_null($booking->duration)) {
            $booking->duration = $checkin_datetime->diffInDays($checkout_datetime);
        }

        $booking->checkout_datetime = $checkout_datetime->format('Y-m-d H:i:00');
        $booking->checkin_datetime  = $checkin_datetime->format('Y-m-d H:i:00');

        if (
            empty($booking->charge_type_id)
            && !empty($booking->type_id)
        ) {
            $booking->charge_type_id = $booking->type_id;
        }

        if (
            empty($booking->total)
            && is_numeric($booking->total_net)
            && is_numeric($booking->vat)
        ) {
            $booking->total = Booking::calculateTotal($booking->total_net, $booking->vat);
        }

        if (
            is_numeric($booking->total)
            && is_numeric($booking->total_paid)
        ) {
            $booking->balance = $booking->total - $booking->total_paid;
        }
    }

    public function creating(Booking $booking)
    {
        if (
            empty($booking->type_id)
            && !empty($booking->vehicle_id)
        ) {
            /** @var Vehicle $vehicle */
            $vehicle = Vehicle::find($booking->vehicle_id);

            if ($vehicle) {
                $booking->type_id = $vehicle->type_id;
            }
        }
    }

    public function created(Booking $booking)
    {
        if (!empty($booking->vehicle_id)) {
            $vehicle_status             = new VehicleStatus();
            $vehicle_status->vehicle_id = $booking->vehicle_id;
            $vehicle_status->user_id    = $booking->user_id;
            $vehicle_status->status     = VehicleStatus::STATUS_BOOKING;
            $vehicle_status->booking_id = $booking->id;

            $vehicle_status->save();
        }
    }
}