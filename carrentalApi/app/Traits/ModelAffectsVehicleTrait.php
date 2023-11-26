<?php

namespace App\Traits;

use Request;

/**
 * @property \App\Vehicle $vehicle
 */
trait ModelAffectsVehicleTrait {
    use ModelBelongsToVehicleTrait;

    public function affectsVehicle(): bool {
        $affectsVehicle = Request::get('affectsVehicle') == 1 ? true : false;
        return $affectsVehicle && $this->canAffectVehicle();
    }

    /**
     * Check if this record is the last for the connected vehicle
     *
     * @return boolean
     */
    public function canAffectVehicle()
    {
        return $this->id == $this->vehicle->getLatestRelatedRecord(static::class)->id;
    }
}
