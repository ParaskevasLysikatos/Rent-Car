<?php

namespace App\Filters;

use Illuminate\Http\Request;

class TransitionFilter extends AbstractFilter
{
    protected $filters = [
        'licence_plates' => 'licence_plate_filter',
        'driver_id' => InFilter::class,
        'type_id' => InFilter::class,
    ];



    public function licence_plate_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('vehicle', function ($q) use ($value) {
            $q->whereHas('license_plates', function ($q2) use ($value) {
                $q2->where('vehicle_id', 'like', '%' . $value . '%');
            });
        });

        return $builder;
    }
}
