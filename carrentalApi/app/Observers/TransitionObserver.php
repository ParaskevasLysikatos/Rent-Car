<?php

namespace App\Observers;

use App\Models\Transition;
use App\Models\VehicleStatus;

class TransitionObserver
{
    /**
     * Handle the transition "created" event.
     *
     * @param \App\Transition $transition
     *
     * @return void
     */
    public function created(Transition $transition)
    {
        if (!empty($transition->vehicle_id)) {
            $vehicle_status                = new VehicleStatus();
            $vehicle_status->user_id       = $transition->user_id;
            $vehicle_status->vehicle_id    = $transition->vehicle_id;
            $vehicle_status->status        = VehicleStatus::STATUS_TRANSITION;
            $vehicle_status->transition_id = $transition->id;

            $vehicle_status->save();
        }
    }

    public function saved(Transition $transition)
    {
        if ($transition->checkedIn()) {
            $vehicle_status = $transition->vehicle_status();
            $vehicle_status->complete();
            if ($transition->affectsVehicle()) {
                $vehicle = $transition->car()->first();
                $vehicle->station_id = $transition->ci_station_id;
                $vehicle->km = $transition->ci_km;
                $vehicle->fuel_level = $transition->ci_fuel_level;
                $vehicle->save();
            }
        }
    }

    public function deleting(Transition $transition) {
        $vehicle_status = $transition->vehicle_status();

        if ($vehicle_status instanceof VehicleStatus) {
            $vehicle_status->complete();
        }
    }
}
