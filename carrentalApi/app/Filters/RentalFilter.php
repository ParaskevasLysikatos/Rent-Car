<?php

namespace App\Filters;

use Illuminate\Support\Carbon;

class RentalFilter extends ReservationFilter
{
    protected $extra_filters = [
        'sequence_number' => LikeFilter::class,
        'vehicle_id' => 'licence_plates_filter',
        'model'=>'model_filter',
        'payer' => 'payer_filter',
        'id'=>InFilter::class,
        'phone'=>'phone_filter',
        'agent_voucher'=>LikeFilter::class,
        'agent_id'=>InFilter::class,
        'sub_account_id'=>'sub_account_filter',
        'program_id'=>InFilter::class,

        'checkout_station_id' => InFilter::class,
        'date_to_out' => 'date_to_out_filter',
        'date_from_out' => 'date_from_out_filter',

        'checkin_station_id' => InFilter::class,
        'date_to_in' => 'date_to_in_filter',
        'date_from_in' => 'date_from_in_filter',
    ];


    public function payer_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('payments', function ($q) use ($value) {
            $q->where('payer_id', 'like', '%' . $value . '%');
        });

        return $builder;
    }

    public function sub_account_filter($builder, $filter, $value)
    {
        $builder = $builder->where('sub_account_id', 'like', '%' . $value . '%');

        return $builder;
    }


    public function phone_filter($builder, $filter, $value)// and email
    {
        $builder = $builder->whereHas('driver', function ($q) use ($value) {
            $q->whereHas('contact', function ($q2) use ($value) {
                $q2->where('phone', 'like', '%' . $value . '%')->orWhere('email', 'like', '%' . $value . '%');
            });
        });


        return $builder;
    }


    public function licence_plates_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('vehicle', function ($q) use ($value) {
            $q->whereHas('license_plates', function ($q2) use ($value) {
                $q2->where('vehicle_id', 'like', '%' . $value . '%');
            });
        });

        return $builder;
    }

    public function model_filter($builder, $filter, $value)
    {

        $builder = $builder->whereHas('vehicle', function ($q) use ($value) {
            $part = explode(" ", $value);
            $q->where('make','like', '%' . $part[0] . '%')->orWhere
            ('model', 'like', '%' . $part[1] . '%');
        });

        return $builder;
    }

    public function date_to_out_filter($builder, $filter, $value)
    {
        $to = Carbon::parse($value)->addDay(1)->format('Y-m-d');
        //echo $to;
        $builder = $builder->where('checkout_datetime', '<=', $to);
    }

    public function date_from_out_filter($builder, $filter, $value)
    {
        $from = Carbon::parse($value)->format('Y-m-d');
        // echo $from;
        $builder = $builder->where('checkout_datetime', '>=', $from);
    }


    public function date_to_in_filter($builder, $filter, $value)
    {
        $to = Carbon::parse($value)->addDay(1)->format('Y-m-d');
        //echo $to;
        $builder = $builder->where('checkin_datetime', '<=', $to);
    }

    public function date_from_in_filter($builder, $filter, $value)
    {
        $from = Carbon::parse($value)->format('Y-m-d');
        // echo $from;
        $builder = $builder->where('checkin_datetime', '>=', $from);
    }


}