<?php

namespace App\Filters;

class DocumentTypeFilter extends AbstractFilter
{
    protected $filters = [
        'title' => 'title_filter',
        'description' => 'description_filter',
    ];


    public function title_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('profiles', function ($licence_plates_q) use ($value) {
            $licence_plates_q->where('title', 'like', '%' . $value . '%');
        });

        return $builder;
    }

    public function description_filter($builder, $filter, $value)
    {
        $builder = $builder->whereHas('profiles', function ($licence_plates_q) use ($value) {
            $licence_plates_q->where('title', 'like', '%' . $value . '%');
        });

        return $builder;
    }



}