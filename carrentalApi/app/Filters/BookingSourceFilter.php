<?php

namespace App\Filters;

use Illuminate\Http\Request;

class BookingSourceFilter extends AbstractFilter
{
    protected $filters = [
        'title' => 'title_filter',
    ];

    public function title_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('profiles', function ($title_q) use ($value) {
            $title_q->where('title', 'like', '%' . $value . '%');
        })->orWhere('slug', 'like', '%' . $value . '%');

        return $builder;
    }
}