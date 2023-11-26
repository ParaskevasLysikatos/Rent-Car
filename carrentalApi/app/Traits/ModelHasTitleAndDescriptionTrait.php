<?php


namespace App\Traits;

use Illuminate\Support\Str;

trait ModelHasTitleAndDescriptionTrait
{
    public function setTitleAttribute($value)
    {
        $value = strval($value);
        $value = trim($value);

        $this->attributes['title'] = $value;
    }

    public function setDescriptionAttribute($value)
    {
        $value = strval($value);
        $value = trim($value);

        if (empty($value)) {
            $value = NULL;
        }

        $this->attributes['description'] = $value;
    }
}
