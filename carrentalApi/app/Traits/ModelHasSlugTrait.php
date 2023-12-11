<?php


namespace App\Traits;

use Illuminate\Support\Str;

trait ModelHasSlugTrait
{
    public function setSlugAttribute($value)
    {
        $value = strval($value);
        $value = Str::slug($value, '-');

        $this->attributes['slug'] = $value;
    }
}
