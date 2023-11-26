<?php

namespace App\Filters;

use Illuminate\Http\Request;

class TagFilter extends AbstractFilter
{
    protected $filters = [
        'title' => LikeFilter::class,
    ];


}