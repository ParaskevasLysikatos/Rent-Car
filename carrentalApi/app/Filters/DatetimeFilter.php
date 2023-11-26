<?php

namespace App\Filters;

use Carbon\Carbon;

class DatetimeFilter
{
    public function filter($builder, $filter, $value)
    {
        if (isset($value['from'])) {
            $from = Carbon::parse($value['from'])->format('Y-m-d H:i:s');
            $builder = $builder->where($filter, '>=', $from );
        }
        if (isset($value['to'])) {
            $to = Carbon::parse($value['to'])->addDay(1)->format('Y-m-d H:i:s');
            $builder = $builder->where($filter, '<=', $to );
        }
        return $builder;
    }
}
