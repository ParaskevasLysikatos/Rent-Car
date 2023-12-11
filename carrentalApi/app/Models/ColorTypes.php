<?php

namespace App;

use App\Filters\ColorTypeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ColorTypes extends Model
{
    protected $fillable = ['id'];


     public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new ColorTypeFilter($request))->filter($builder);
    }

}
