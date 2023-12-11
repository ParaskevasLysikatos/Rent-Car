<?php

namespace App\Filters;

use Illuminate\Http\Request;

class PlaceFilter extends AbstractFilter
{
    protected $filters = [
        'title' => 'title_filter',
        'stations' => 'station_filter',
        'exclude_id' => 'notInFilter',
        'include_id' => 'InFilter'
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

    public function title_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('profiles', function ($title_q) use ($value) {
            $title_q->where('title', 'like', '%' . $value . '%');
        });

        return $builder;
    }

    public function station_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('stations', function ($q) use ($value) {
            $q->whereHas('profiles', function ($q2) use ($value) {
                $q2->where('title', 'like', '%' . $value . '%');
            });
        });

        return $builder;
    }
}