<?php


namespace Tests\Model;

use App\Pricelist;
use App\PricelistRange;
use Exception;
use Tests\TestCase;

class PricelistRangeTest extends TestCase
{
    protected $truncate = [
        'pricelist_ranges',
        'pricelists',
    ];

    public function test_PricelistRangeObserver_min_max_days_create()
    {
        $this->expectException(Exception::class);

        $pricelist = factory(Pricelist::class)->create();

        $pricelist_range               = new PricelistRange();
        $pricelist_range->pricelist_id = $pricelist->id;
        $pricelist_range->minimum_days = 4;
        $pricelist_range->maximum_days = 3;
        $pricelist_range->cost         = 10.5;

        $pricelist_range->save();
    }

    public function test_PricelistRangeObserver_min_max_days_update()
    {
        $this->expectException(Exception::class);

        $pricelist = factory(Pricelist::class)->create();

        $pricelist_range               = new PricelistRange();
        $pricelist_range->pricelist_id = $pricelist->id;
        $pricelist_range->minimum_days = 3;
        $pricelist_range->maximum_days = 4;
        $pricelist_range->cost         = 10.5;

        $pricelist_range->save();

        $pricelist_range->minimum_days = 5;

        $pricelist_range->save();
    }
}
