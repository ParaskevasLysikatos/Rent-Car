<?php

namespace App\Filters;

class DocumentFilter extends AbstractFilter
{
    protected $filters = [
        'id' => LikeFilter::class,
        'name' => LikeFilter::class,
        'mime_type'=> LikeFilter::class,
        'user_id'=>InFilter::class,
    ];

}