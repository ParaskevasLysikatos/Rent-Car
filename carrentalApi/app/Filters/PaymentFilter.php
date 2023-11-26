<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentFilter extends AbstractFilter
{
    protected $filters = [
        'id' => InFilter::class,
        'payment_datetime' => DatetimeFilter::class,
        'station_id' => InFilter::class,
        'payer_id' => EqualFilter::class,
        'payer_type' => EqualFilter::class,
        'method' => InFilter::class,
        'user_id' => EqualFilter::class,

        'payer'=>'payer_filter',

        'date_to'=> 'date_to_filter',
        'date_from' => 'date_from_filter',
        'sequence_number'=>LikeFilter::class,
        'rental_id'=>'rental_filter',

    ];

    public function rental_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('rentals', function ($q) use ($value) {
            $q->where('payment_link_id', 'like', '%' . $value . '%');
        });

        return $builder;
    }


    public function payer_filter($builder, $filter, $value)
    {
        $builder = $builder->where('payer_id', 'like', '%' . $value . '%');

        return $builder;
    }

    public function date_to_filter($builder, $filter, $value)
    {
        $to = Carbon::parse($value)->addDay(1)->format('Y-m-d');
        //echo $to;
        $builder = $builder->where('payment_datetime', '<=', $to);
    }

    public function date_from_filter($builder, $filter, $value)
    {
        $from = Carbon::parse($value)->format('Y-m-d');
       // echo $from;
        $builder = $builder->where('payment_datetime', '>=', $from);
    }

}
