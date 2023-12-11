<?php

namespace App\Traits;

use Lang;

trait ModelHasProfileTitleAttributeTrait {
    public abstract function profiles();

    public function current_profile() {
        return $this->profiles()->where('language_id', Lang::getLocale());
    }

    public function getProfileTitleAttribute() {
        return $this->current_profile()->first()->title ?? null;
    }
}
