<?php

namespace App\Observers;

use App\Models\Maintenance;
use App\Models\VehicleStatus;

class MaintenanceObserver
{
    public function created(Maintenance $maintenance)
    {
        if (!empty($maintenance->vehicle_id)) {
            $vehicle_status                 = new VehicleStatus();
            $vehicle_status->user_id        = $maintenance->user_id;
            $vehicle_status->vehicle_id     = $maintenance->vehicle_id;
            $vehicle_status->status         = VehicleStatus::STATUS_MAINTENANCE;
            $vehicle_status->maintenance_id = $maintenance->id;

            $vehicle_status->save();
        }
    }
}
