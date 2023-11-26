<?php

namespace App\Filters;

use Illuminate\Support\Carbon;

class BookingFilter extends ReservationFilter
{
    protected $extra_filters = [
        'sequence_number' =>LikeFilter::class,
        'duration'=>LikeFilter::class,
        'group' =>'group_filter',
        'payer'=> 'payer_filter',
        'phone'=>'phone_filter',
        'cancel_reason_id'=>InFilter::class,

        'date_to_out' => 'date_to_out_filter',
        'date_from_out' => 'date_from_out_filter',

        'date_to_in' => 'date_to_in_filter',
        'date_from_in' => 'date_from_in_filter',

        'date_to_at' => 'date_to_at_filter',
        'date_from_at' => 'date_from_at_filter',

        'sub_account' => 'sub_account_filter',
    ];


    public function group_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('type', function ($q) use ($value) {
            $q->whereHas('category', function ($q2) use ($value) {
                $q2->whereHas('profiles', function ($q3) use ($value) {
                    $q3->where('title', 'like', '%' . $value . '%');
                });
            });
        });

        return $builder;
    }

    public function payer_filter($builder, $filter, $value)
    {
        $builder = $builder->where('customer_id', $value);

        return $builder;
    }

    public function phone_filter($builder, $filter, $value)//and email
    {
        $builder = $builder->where('phone', 'like', '%' . $value . '%')
        ->orWhereHasMorph('customer','*',function ($q) use ($value) {
            $q->whereHas('contact', function ($q2) use ($value) {
                $q2->where('email', 'like', '%' . $value . '%');
            });
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

    public function sub_account_filter($builder, $filter, $value)
    {
        $builder = $builder->where('sub_account_id', 'like', '%' . $value . '%');

        return $builder;
    }

    public function date_to_at_filter($builder, $filter, $value)
    {
        $to = Carbon::parse($value)->addDay(1)->format('Y-m-d');
        //echo $to;
        $builder = $builder->where('created_at', '<=', $to);
    }

    public function date_from_at_filter($builder, $filter, $value)
    {
        $from = Carbon::parse($value)->format('Y-m-d');
        // echo $from;
        $builder = $builder->where('created_at', '>=', $from);
    }



}



//reservation filter

//  'checkout_datetime' => DatetimeFilter::class,
//         'checkin_datetime' => DatetimeFilter::class,
//         'checkout_station_id' => InFilter::class,
//         'checkin_station_id' => InFilter::class,
//         'status' => InFilter::class,
//         'agent_id' => InFilter::class,
//         'source_id' => InFilter::class,
//         'sub_account_id' => EqualFilter::class,
//         'sub_account_type' => EqualFilter::class
