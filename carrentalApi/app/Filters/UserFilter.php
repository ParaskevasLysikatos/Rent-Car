<?php

namespace App\Filters;

class UserFilter extends AbstractFilter
{
    protected $filters = [
        'name' => LikeFilter::class,
        'email' => LikeFilter::class,
        'phone' => LikeFilter::class,
    ];


}