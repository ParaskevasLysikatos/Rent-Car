<?php

namespace App\Filters;

class ColorTypeFilter extends AbstractFilter
{
    protected $filters = [
        'title' => LikeFilter::class,
    ];
}
