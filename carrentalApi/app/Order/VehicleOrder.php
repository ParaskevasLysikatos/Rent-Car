<?php

namespace App\Order;

use App\Traits\ModelIsSortableTrait;

trait VehicleOrder
{
    use ModelIsSortableTrait;

    public function orderByLicencePlate($builder, $order) {
        $builder->leftJoinRelationship('license_plates', function ($join) {
            $join->whereRaw('(licence_plates.vehicle_id, licence_plates.registration_date) IN (SELECT vehicle_id, max(registration_date) as registration_date FROM licence_plates GROUP BY vehicle_id)');
        })->orderBy('licence_plates.licence_plate', $order);
    }
}
