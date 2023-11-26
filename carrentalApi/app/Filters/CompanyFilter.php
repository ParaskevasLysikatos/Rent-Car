<?php

namespace App\Filters;

class CompanyFilter extends AbstractFilter
{
    protected $filters = [
        'foreign_afm' => EqualFilter::class,
        'afm'=>LikeFilter::class,
        'phone'=>EqualFilter::class,
        'name'=>'name_filter'
    ];


    public function name_filter($builder, $filter, $value)
    {
        $builder = $builder->where('name', 'like', '%' . $value . '%')
        ->orWhere('title', 'like', '%' . $value . '%');

        return $builder;
    }


}