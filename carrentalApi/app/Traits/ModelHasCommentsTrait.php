<?php


namespace App\Traits;

trait ModelHasCommentsTrait
{
    public function setCommentsAttribute($value)
    {
        $value = strval($value);
        $value = trim($value);

        if (empty($value)) {
            $value = NULL;
        }

        $this->attributes['comments'] = $value;
    }
}
