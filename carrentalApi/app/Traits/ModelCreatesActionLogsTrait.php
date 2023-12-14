<?php

namespace App\Traits;

use App\Models\ActionLog;
use App\Observers\ActionObserver;


trait ModelCreatesActionLogsTrait {
    public static function bootModelCreatesActionLogsTrait() {
        static::observe(ActionObserver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function action_logs() {
        $this->morphToMany(ActionLog::class, 'action_log');
    }
}
