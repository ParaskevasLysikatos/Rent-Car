<?php

namespace App\Filters;
use Carbon\Carbon;
use App\Models\Type;

class TypeFilter extends AbstractFilter
{
    protected $filters = [
        'title'=>'title_filter',
        'category' => 'category_filter'
    ];


    public function title_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('profiles', function ($title_q) use ($value) {
            $title_q->where('title', 'like', '%' . $value . '%');
        });

        return $builder;
    }

    public function category_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('category', function ($title_q) use ($value) {
            $title_q->where('slug', 'like', '%' . $value . '%');
        });

        return $builder;
    }





}
