<?php

namespace App\Observers;

class PrefixObserver
{
    public function creating($object)
    {
        $object->generatePrefixName();
    }
}
