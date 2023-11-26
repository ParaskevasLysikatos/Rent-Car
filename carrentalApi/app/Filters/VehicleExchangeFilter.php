<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VehicleExchangeFilter extends AbstractFilter
{
    protected $filters = [
        'new_vehicle' => 'new_vehicle_filter',
        'old_vehicle' => 'old_vehicle_filter',
        'datetime' => 'date_filter',
        'rental_id'=>InFilter::class,
    ];



    public function new_vehicle_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('new_vehicle', function ($q) use ($value) {
            $q->whereHas('license_plates', function ($q2) use ($value) {
                $q2->where('vehicle_id', 'like', '%' . $value . '%');
            });
        });

        return $builder;
    }


    public function old_vehicle_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('old_vehicle', function ($q) use ($value) {
            $q->where('id', 'like', '%' . $value . '%');
        });
        return $builder;
    }


    public function date_filter($builder, $filter, $value)
    {
            $from = Carbon::parse($value);
            $to = Carbon::parse($value)->addHours(18);
           // echo $value;
           // echo '  '.$from;
            $builder = $builder->whereBetween('datetime',[$from,$to]);

        return $builder;
    }

}