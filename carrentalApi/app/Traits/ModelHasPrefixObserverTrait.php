<?php

namespace App\Traits;

use App\Models\Station;

trait ModelHasPrefixObserverTrait {
    public abstract function getPrefix();
    public abstract static function getPrefixField();
    // public abstract static function getStationField();
    public abstract function getPrefixName();

    public static function bootModelHasPrefixObserverTrait() {
        static::creating(function ($object) {
            if (!$object->manual_agreement) {
                $object->generatePrefixName();
            }
        });
        static::deleted(function ($object) {
            $object->changeStationNumber($object);
        });
    }

    public function generatePrefixName() {
        $field = self::getPrefixField();
        $station_field = $this->getStationField();
        $current_prefix = $this->getPrefixName();

        $station = Station::find($this->{$field});
        $number = ++$station->{$station_field};

        $this->sequence_number = $this->getPrefix() . '-'. $current_prefix . '-' . $number;

        $station->save();
    }

    public function changeStationNumber($object) {
        $field = self::getPrefixField();
        $station_field = $this->getStationField();
        $current_prefix = $this->getPrefixName();

        $station = Station::find($this->{$field});
        $number = $station->{$station_field};

        $sequence_number = $this->getPrefix() . '-'. $current_prefix . '-' . $number;
        if ($sequence_number == $object->sequence_number) {
            $station->{$station_field}--;
            $station->save();
        }
    }
}
