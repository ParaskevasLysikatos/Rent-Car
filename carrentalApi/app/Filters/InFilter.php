<?php

namespace App\Filters;

class InFilter
{
    public function filter($builder, $filter, $value)
    {
        $value = is_array($value) ? $value : [$value];
        $builder = $builder->whereIn($filter, $value);
        return $builder;
    }
}