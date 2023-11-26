<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    protected $request;

    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function filter(Builder $builder)
    {
        foreach($this->getFilters() as $filter => $value)
        {
            $this->resolveFilter($builder, $filter, $value);
        }
        return $builder;
    }

    protected function getFilters()
    {
        return array_filter($this->request->only(array_keys($this->filters)), function ($input) {
            return $input === 0 || $input === '0' || !empty($input);
        });
    }

    protected function resolveFilter($builder, $filter, $value)
    {
        $filtered_value = null;
        if (class_exists($this->filters[$filter])) {
            $filtered_value = (new $this->filters[$filter])->filter($builder, $filter, $value);
        } else {
            $filtered_value = $this->{$this->filters[$filter]}($builder, $filter, $value);
        }
        return $filtered_value;
    }
}
