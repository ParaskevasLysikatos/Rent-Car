<?php

namespace App\Filters;

use App\Filters\AbstractFilter;

class DriverFilter extends AbstractFilter
{
    protected $filters = [
       // 'firstname' => 'firstname_filter',
      //  'lastname' => 'lastname_filter',
        'id' => InFilter::class,
        'exclude_id'=> 'notInFilter',
        'include_id' => 'InFilter',
        'phone'=> 'phone_filter',
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

     public function phone_filter($builder, $filter, $value)
     {
         $builder = $builder->whereHas('contact', function ($title_q) use ($value) {
           $title_q->where('phone', 'like', '%' . $value . '%');
       });

       return $builder;
     }

    // public function firstname_filter($builder, $filter, $value)
    // {
    //     $builder = $builder->whereHas('contact', function ($title_q) use ($value) {
    //         $title_q->where('firstname', 'like', '%' . $value . '%');
    //     });

    //     return $builder;
    // }

    // public function lastname_filter($builder, $filter, $value)
    // {
    //     $builder = $builder->whereHas('contact', function ($title_q) use ($value) {
    //         $title_q->where('lastname', 'like', '%' . $value . '%');
    //     });

    //     return $builder;
    // }
}
