<?php

namespace App\Observers;

use App\Rental;
use App\Exceptions\InvalidDatesException;
use App\Vehicle;
use App\VehicleStatus;
use Carbon\Carbon;

class RentalObserver
{
    public function saving(Rental $rental)
    {
        $checkout_datetime = Carbon::parse($rental->checkout_datetime);
        $checkin_datetime  = Carbon::parse($rental->checkin_datetime);

        if ($checkin_datetime->lessThan($checkout_datetime)) {
            throw new InvalidDatesException('checkout datetime cannot be later than checking datetime.');
        }

        if (is_null($rental->duration)) {
            $rental->duration = $checkin_datetime->diffInDays($checkout_datetime);
        }

        $rental->checkout_datetime = $checkout_datetime->format('Y-m-d H:i:00');
        $rental->checkin_datetime  = $checkin_datetime->format('Y-m-d H:i:00');

        if (
            empty($rental->charge_type_id)
            && !empty($rental->type_id)
        ) {
            $rental->charge_type_id = $rental->type_id;
        }

        if (
            empty($rental->total)
            && is_numeric($rental->total_net)
            && is_numeric($rental->vat)
        ) {
            $rental->total = Rental::calculateTotal($rental->total_net, $rental->vat);
        }

        if (
            is_numeric($rental->total)
            && is_numeric($rental->total_paid)
        ) {
            $rental->balance = $rental->total - $rental->total_paid;
        }
    }

    public function saved(Rental $rental) {
        if ($rental->canAffectVehicle()) {
            $km = $rental->checkin_km ?? $rental->checkout_km;
            $fuel = $rental->checkin_fuel_level ?? $rental->checkout_fuel_level;

            $vehicle = $rental->vehicle;
            $vehicle->km = $km;
            $vehicle->fuel_level = $fuel;

            $station_id = $rental->checkout_station_id;
            $place_id = $rental->checkout_place_id;
            $place_text = $rental->checkout_place_text;
            if (in_array($rental->status,
                [Rental::STATUS_PRE_CHECKED_IN, Rental::STATUS_CHECKED_IN, Rental::STATUS_CLOSED, Rental::STATUS_COMPLETED]
            )) {
                $station_id = $rental->checkin_station_id;
                $place_id = $rental->checkin_place_id;
                $place_text = $rental->checkin_place_text;
            }
            $vehicle->station_id = $station_id;
            $vehicle->place_id = $place_id;
            $vehicle->place_text = $place_text;

            $vehicle->save();
        }

        if ($rental->status == Rental::STATUS_CLOSED) {
            $rental->load('transactions');
            $transactions = $rental->transactions;
            foreach ($transactions as $transaction) {
                $transaction->createInvoice($rental);
            }
        }
    }

    public function creating(Rental $rental)
    {
        if (
            empty($rental->type_id)
            && !empty($rental->vehicle_id)
        ) {
            /** @var Vehicle $vehicle */
            $vehicle = Vehicle::find($rental->vehicle_id);

            if ($vehicle) {
                $rental->type_id = $vehicle->type_id;
            }
        }
    }

    public function created(Rental $rental)
    {
        if (!empty($rental->vehicle_id)) {
            $vehicle_status             = new VehicleStatus();
            $vehicle_status->vehicle_id = $rental->vehicle_id;
            $vehicle_status->user_id    = $rental->user_id;
            $vehicle_status->status     = VehicleStatus::STATUS_RENTAL;
            $vehicle_status->rental_id = $rental->id;

            $vehicle_status->save();
        }
    }
}