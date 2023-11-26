<?php


namespace App\Traits;

use App\Scopes\ReservationsStationScope;

trait ReservationsTrait
{
    protected static function bootReservationsTrait()
    {
        // static::addGlobalScope(new ReservationsStationScope());
    }
}
