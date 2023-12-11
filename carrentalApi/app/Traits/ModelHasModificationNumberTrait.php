<?php

namespace App\Traits;

trait ModelHasModificationNumberTrait {
    public static function bootModelHasModificationNumberTrait() {
        static::saving(function ($object) {
            if ($object->exists && $object->isDirty()) {
                $object->modification_number++;
            }
        });
    }
}
