<?php

namespace App\Traits;

use Lang;

trait ModelHasProfileDescriptionAttributeTrait {
    public abstract function profiles();

    public function getProfileDescriptionAttribute() {
        return $this->profiles()->where('language_id', Lang::getLocale())->first()->description;
    }
}
