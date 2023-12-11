<?php

namespace App\Filters;

class EqualFilter
{
    public function filter($builder, $filter, $value)
    {
        $builder = $builder->where($filter, $value);
        return $builder;
    }
}
