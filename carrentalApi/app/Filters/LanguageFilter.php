<?php

namespace App\Filters;

use Illuminate\Http\Request;

class LanguageFilter extends AbstractFilter
{
    protected $filters = [
        'title' => 'title_filter',
    ];

    public function title_filter($builder, $filter, $value)
    {
        $builder = $builder->where('title', 'like', '%' . $value . '%')
        ->orWhere('id', 'like', '%' . $value . '%')
        ->orWhere('title_international', 'like', '%' . $value . '%');

        return $builder;
    }


}