<?php

namespace App\Filters;

use Illuminate\Http\Request;

class StationFilter extends AbstractFilter
{
    protected $filters = [
        'title' => 'title_filter',
        'location' => 'location_filter',
        'id'=>InFilter::class,
    ];

    public function title_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('profiles', function ($title_q) use ($value) {
            $title_q->where('title', 'like', '%' . $value . '%');
        });

        return $builder;
    }

    public function location_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('location', function ($q) use ($value) {
            $q->whereHas('profiles', function ($q2) use ($value) {
                $q2->where('title', 'like', '%' . $value . '%');
            });
        });

        return $builder;
    }
}