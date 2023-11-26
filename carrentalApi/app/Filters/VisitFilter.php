<?php

namespace App\Filters;

use Carbon\Carbon;

class VisitFilter extends AbstractFilter
{
    protected $filters = [
        'licence_plate' => 'licence_plate_filter',
        'vehicle' => 'vehicle_filter',
    ];

    public function licence_plate_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('vehicle', function ($plate_q) use ($value)  {
            $plate_q->whereHas('license_plates', function ($plates2_q) use ($value){
            $plates2_q->where('licence_plate', 'like', '%' . $value . '%');
            });
        });

        return $builder;
    }

    public function vehicle_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('vehicle', function ($licence_plates_q) use ($value) {
            $licence_plates_q->where('model', 'like', '%' . $value . '%')->orWhere('make', 'like', '%' . $value . '%');
        });

        return $builder;
    }


}
