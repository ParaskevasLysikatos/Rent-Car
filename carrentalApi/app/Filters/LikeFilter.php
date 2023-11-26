<?php

namespace App\Filters;

class LikeFilter
{
    public function filter($builder, $filter, $value)
    {
        $builder = $builder->where($filter, 'like', $value.'%');
        return $builder;
    }
}
