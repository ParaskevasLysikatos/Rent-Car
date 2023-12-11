<?php


namespace App\Observers;


use App\Exceptions\InvalidRangeException;
use App\PricelistRange;

class PricelistRangeObserver
{

    public function saving(PricelistRange $pricelist_range)
    {
        if ($pricelist_range->minimum_days < 1) {
            $pricelist_range->minimum_days = 1;
        }

        if ($pricelist_range->minimum_days > $pricelist_range->maximum_days) {
            throw new InvalidRangeException('minimum_days cannot be larger than maximum_days');
        }
    }

}
