<?php

namespace App\Filters;

use App\VehicleReservations;
use Carbon\Carbon;

class VehicleFilter extends AbstractFilter
{
    protected $filters = [
        'status_id' => InFilter::class,
       'type_id2' => 'type_id_filter',
        'type_id' => InFilter::class,
        'station_id' => InFilter::class,
        'place_text' => LikeFilter::class,
        'ownership_type_id' => InFilter::class,
        'status2' => 'status2', //takes string like rental or not (v2)
        'status' => 'status',//v1

        'place' => LikeFilter::class,
        'licence_plates'=> 'licence_plates_filter',
        'vehicle_status' => 'vehicle_status_filter',
        'exclude_id' => 'notInFilter',
        'include_id' => 'InFilter',
        'availability'=> 'availability_filter',
        'import_to_system'=> 'import_to_system_filter',
        'purchase_date'=>'purchase_date_filter'
    ];

    public function notInFilter($builder, $filter, $value)
    {
        $value = is_array($value) ? $value : [$value];
        $builder = $builder->whereNotIn('id', $value);
        return $builder;
    }

    public function InFilter($builder, $filter, $value)
    {
        $value = is_array($value) ? $value : [$value];
        $builder = $builder->whereIn('id', $value);
        return $builder;
    }

    public function type_id_filter($builder, $filter, $value)
    {
        $value = is_array($value) ? implode("", $value) : $value;
        $builder = $builder->where('type_id','=',(int)$value);
        return $builder;
    }

    public function licence_plates_filter($builder, $filter, $value) {
        $builder = $builder->whereHas('license_plates', function ($licence_plates_q) use ($value) {
            $licence_plates_q->where('licence_plate', 'like', '%'.$value.'%');
        });

        return $builder;
    }


    public function vehicle_status_filter($builder, $filter, $value)
    {
        $value = is_array($value) ? implode("", $value) : $value;
        $builder = $builder->whereHas('vehicle_status', function ($vehicle_status_q) use ($value) {
            $vehicle_status_q->where('slug', 'like', '%' . $value . '%');
        });

        return $builder;
    }

    public function status2($builder, $filter, $value) {
        // if ($value) {
        //     $date = Carbon::parse($this->request->availability)->toDate();
        //     $reserved_ids = VehicleReservations::getReservedVehicles($date, $date);
        //     if ($value == 'rental') {
        //         $builder = $builder->whereIn('vehicles.id', $reserved_ids);
        //     } else {
        //         $builder = $builder->whereNotIn('vehicles.id', $reserved_ids);
        //     }
        // }
        $value = is_array($value) ? implode("",$value) : $value;
        if($value=='rental') {
        $builder = $builder->where('status', 'like', '%' . $value . '%');
        }
        else{
            $builder = $builder->where('status', 'like', '%' . $value . '%')
            ->orWhere('status',null);
        }
        return $builder;
    }

    public function availability_filter($builder, $filter, $value)
    {
          if ($value) {
            $date = Carbon::parse($value)->toDate();
            $reserved_ids = VehicleReservations::getReservedVehicles($date, $date);
            if ($value == 'rental') {
                $builder = $builder->whereIn('vehicles.id', $reserved_ids);
            } else {
                $builder = $builder->whereNotIn('vehicles.id', $reserved_ids);
            }
        }
    }

    public function import_to_system_filter($builder, $filter, $value)
    {
        $import_to_system = Carbon::parse($value)->format('Y-m-d');
        //echo $to;
        $builder = $builder->whereDate('import_to_system','=', $import_to_system);
    }

    public function purchase_date_filter($builder, $filter, $value)
    {
        $purchase_date = Carbon::parse($value)->format('Y-m-d');
        //echo $to;
        $builder = $builder->whereDate('purchase_date', '=', $purchase_date);
    }

    public function status($builder, $filter, $value)
    {
        if ($value) {
            $date = Carbon::parse($this->request->availability)->toDate();
            $reserved_ids = VehicleReservations::getReservedVehicles($date, $date);
            if ($value == 'rental') {
                $builder = $builder->whereIn('vehicles.id', $reserved_ids);
            } else {
                $builder = $builder->whereNotIn('vehicles.id', $reserved_ids);
            }
        }
        return $builder;
    }

}