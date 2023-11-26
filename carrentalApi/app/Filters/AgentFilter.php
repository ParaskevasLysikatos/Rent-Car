<?php

namespace App\Filters;

class AgentFilter extends AbstractFilter
{
    protected $filters = [
        'id' => InFilter::class,
        'exclude_id' => 'notInFilter',
        'include_id' => 'InFilter'
    ];

    public function notInFilter($builder, $filter, $value)
    {
        $value = is_array($value) ? $value : [$value];
        $builder = $builder->whereNotIn('id', $value);
        return $builder;
    }

    public function InFilter($builder, $filter, $value)
    {
        $value = is_array($value) ? $value : [$value];
        $builder = $builder->whereIn('id', $value);
        return $builder;
    }

}
