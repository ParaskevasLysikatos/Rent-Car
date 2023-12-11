<?php

namespace App\Filters;

class NotInFilter
{
    public function filter($builder, $filter, $value)
    {
        $value = is_array($value) ? $value : [$value];
        $builder = $builder->whereNotIn($filter, $value);
        return $builder;
    }
}
