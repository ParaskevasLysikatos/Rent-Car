<?php

namespace App\Traits;

interface ModelHasStatusInterface
{
    public static function getValidStatuses(): array;

    public function setStatusAttribute($value);
}
