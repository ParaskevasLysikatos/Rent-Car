<?php

namespace App\Observers;

use App\ServiceVisit;

class VisitObserver
{
    /**
     * Handle the service visit "saved" event.
     *
     * @param  \App\ServiceVisit  $serviceVisit
     * @return void
     */
    public function saved(ServiceVisit $serviceVisit)
    {
        if ($serviceVisit->affectsVehicle()) {
            $vehicle = $serviceVisit->vehicle()->first();
            $vehicle->km = $serviceVisit->km;
            $vehicle->fuel_level = $serviceVisit->fuel_level;
            $vehicle->save();
        }
    }
}
