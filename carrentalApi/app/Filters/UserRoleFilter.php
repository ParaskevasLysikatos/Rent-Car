<?php

namespace App\Filters;

class UserRoleFilter extends AbstractFilter
{
    protected $filters = [
        'title' => LikeFilter::class,
    ];
}
