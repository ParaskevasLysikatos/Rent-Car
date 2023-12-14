<?php

namespace App\Traits;

use App\Models\Driver;
use App\Models\DriverLink;
use Request;

trait ModelHasDriversTrait {

    public static function bootModelHasDriversTrait() {
        static::saved(function ($object) {
           // $object->handleDrivers(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function drivers()
    {
        return $this->morphToMany(Driver::class, 'driver_link')->orderBy('id');
    }

    /**
     * Remove driver association with current model
     *
     * @param \App\Driver $driver
     *
     * @return \App\static:class model class
     */
    public function removeDriverLink($driver_id) {
        $this->drivers()->detach($driver_id);
    }

    /**
     * Associate driver with current model
     *
     * @param \App\Driver $driver
     *
     * @return \App\static:class model class
     */
    public function addDriverLink(Driver $driver)
    {
        $driver_link = DriverLink::where(['driver_id' => $driver->id, 'driver_link_id' => $this->id, 'driver_link_type' => static::class])->first();

        if (!$driver_link) {
            $driver_link                     = new DriverLink();
            $driver_link->driver_link_id     = $this->id;
            $driver_link->driver_link_type   = static::class;
            $driver_link->driver_id          = $driver->id;

            $driver_link->save();
        }

        return $this;
    }

    public function addDrivers($drivers) {
        $existingDrivers = $this->drivers()->get()->pluck('id');
        $driversToAdd = Driver::whereIn('id', $drivers)->whereNotIn('id', $existingDrivers)->get();
        foreach ($driversToAdd as $driver){
            $this->addDriverLink($driver);
        }
    }

    public function removeDrivers($driver_ids) {
        foreach ($driver_ids as $driver_id) {
            $this->removeDriverLink($driver_id);
        }
    }

    public function handleDrivers() {
        $drivers = Request::get('drivers');
        if (Request::has('drivers')) {
            if (!is_array($drivers)) {
                $drivers = [$drivers];
            }
            $this->addDrivers($drivers);
            $existingDrivers = $this->drivers()->get()->pluck('id');
            $driversToRemove = array_diff($existingDrivers->toArray(), $drivers);
            $this->removeDrivers($driversToRemove);
        }
    }
}
