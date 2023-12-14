<?php

namespace App\Observers;

use App\Exceptions\VehicleHasActiveStatusesException;
use App\Exceptions\VehicleNotFoundException;
use App\Models\Vehicle;
use App\Models\VehicleStatus;

class VehicleStatusObserver
{
    public function creating(VehicleStatus $vehicle_status)
    {
        $vehicle = Vehicle::find($vehicle_status->vehicle_id);
        if (!$vehicle) {
            throw new VehicleNotFoundException('VehicleStatus ' . $vehicle_status->vehicle_id . ' references a missing vehicle');
        }

        /**
         * We don't want a new status if old ones haven't been properly completed
         */
        $active_statuses = VehicleStatus::where(['vehicle_id' => $vehicle_status->vehicle_id, 'completed_at' => NULL])->count();
        if ($active_statuses > 0) {
            throw new VehicleHasActiveStatusesException('Vehicle ' . $vehicle_status->vehicle_id . ' has active statuses');
        }
    }

    // public function created(VehicleStatus $vehicle_status)
    // {
    //     $vehicle = Vehicle::find($vehicle_status->vehicle_id);
    //     if (!$vehicle) {
    //         throw new VehicleNotFoundException('VehicleStatus ' . $vehicle_status->id . ' references a missing vehicle');
    //     }

    //     $vehicle->status = $vehicle_status->status;

    //     $vehicle->save();
    // }

    public function saving(VehicleStatus $vehicle_status)
    {
        if ($vehicle_status->status == VehicleStatus::STATUS_RENTAL) {
            $vehicle_status->transition_id  = NULL;
            $vehicle_status->maintenance_id = NULL;

            if (empty($vehicle_status->rental_id)) {
                throw new \InvalidArgumentException('Status ' . VehicleStatus::STATUS_RENTAL . ' requires a rental_id.');
            }
        }

        if ($vehicle_status->status == VehicleStatus::STATUS_TRANSITION) {
            $vehicle_status->rental_id      = NULL;
            $vehicle_status->maintenance_id = NULL;

            if (empty($vehicle_status->transition_id)) {
                throw new \InvalidArgumentException('Status ' . VehicleStatus::STATUS_TRANSITION . ' requires a transition_id.');
            }
        }

        if ($vehicle_status->status == VehicleStatus::STATUS_MAINTENANCE) {
            $vehicle_status->rental_id      = NULL;
            $vehicle_status->transition_id = NULL;

            if (empty($vehicle_status->maintenance_id)) {
                throw new \InvalidArgumentException('Status ' . VehicleStatus::STATUS_MAINTENANCE . ' requires a maintenance_id.');
            }
        }
    }

    public function saved(VehicleStatus $vehicle_status)
    {
        $vehicle = $vehicle_status->car()->first();
        if (!$vehicle) {
            throw new VehicleNotFoundException('VehicleStatus ' . $vehicle_status->id . ' references a missing vehicle');
        }

        if (!$vehicle_status->completed_at) {
            $vehicle->status = $vehicle_status->status;
        } else {
            $vehicle->status = Vehicle::STATUS_AVAILABLE;
        }
        $vehicle->save();
    }
}
