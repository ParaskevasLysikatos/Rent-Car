<?php


namespace App\Traits;

trait ModelWithQuietlySave
{
    public function saveWithoutEvents(array $options=[])
    {
        return static::withoutEvents(function() use ($options) {
            return $this->save($options);
        });
    }
}
