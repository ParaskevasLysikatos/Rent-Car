<?php

namespace App\Filters;

use Carbon\Carbon;

class BrandFilter extends AbstractFilter
{
    protected $filters = [
        'title' => 'title_filter',
    ];

    public function title_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('profiles', function ($title_q) use ($value) {
            $title_q->where('title', 'like', '%' . $value . '%');
        });

        return $builder;
    }
}