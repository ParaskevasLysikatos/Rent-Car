<?php

namespace App\Traits;

use App\OptionInstance;

trait ModelHasOptionInstancesTrait {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function option_instances() {
        return $this->morphToMany(OptionInstance::class, 'process')->orderBy('id');
    }
}
