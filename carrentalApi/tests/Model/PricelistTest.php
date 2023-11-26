<?php


namespace Tests\Model;

use App\Pricelist;
use Carbon\Carbon;
use Tests\TestCase;

class PricelistTest extends TestCase
{

    protected $truncate = [
        'pricelist_ranges',
        'pricelists',
    ];

    public function test_getCostFromNumberOfDays()
    {
        /** @var Pricelist $pricelist */
        $pricelist = factory(Pricelist::class)->create([
            'extra_day_cost' => 5,
        ]);

        $pricelist->addPricelistRange(1, 3, 10);
        $pricelist->addPricelistRange(4, 5, 9.5);
        $pricelist->addPricelistRange(6, 6, 8);
        $pricelist->addPricelistRange(7, 10, 7.5);

        $days     = 2;
        $expected = $days * 10; // range 1

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));

        $days     = 3;
        $expected = $days * 10; // range 1

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));

        $days     = 4;
        $expected = $days * 9.5; // range 2

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));

        $days     = 6;
        $expected = $days * 8; // range 3

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));

        $days     = 7;
        $expected = $days * 7.5; // range 4

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));

        $days     = 8;
        $expected = $days * 7.5; // range 4

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));

        $days     = 10;
        $expected = $days * 7.5; // range 4

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));

        $days     = 11;
        $expected = $days * 5; // outside ranges, fall back to extra day

        $this->assertEquals($expected, $pricelist->getCostFromNumberOfDays($days));
    }

    public function test_getNumberOfDaysFromDates()
    {
        $from      = 0;
        $to        = 5;
        $expected  = 5;
        $date_from = (new Carbon())->modify($from . ' days');
        $date_to   = (new Carbon())->modify($to . ' days');

        $result = Pricelist::getNumberOfDaysFromDates($date_from, $date_to);

        $this->assertEquals($expected, $result);

        $from      = 3;
        $to        = 12;
        $expected  = 9;
        $date_from = (new Carbon())->modify($from . ' days');
        $date_to   = (new Carbon())->modify($to . ' days');

        $result = Pricelist::getNumberOfDaysFromDates($date_from, $date_to);

        $this->assertEquals($expected, $result);

        $from      = 5;
        $to        = 4;
        $expected  = 0;
        $date_from = (new Carbon())->modify($from . ' days');
        $date_to   = (new Carbon())->modify($to . ' days');

        $result = Pricelist::getNumberOfDaysFromDates($date_from, $date_to);

        $this->assertEquals($expected, $result);
    }
}
