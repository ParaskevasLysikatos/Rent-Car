<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property float  $extra_day_cost
 */
class Pricelist extends Model
{
    use SoftDeletes;

    protected $casts = [
        'extra_day_cost' => 'float',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(PricelistProfile::class, 'pricelist_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ranges()
    {
        return $this->hasMany(PricelistRange::class, 'pricelist_id', 'id');
    }

    public function addPricelistRange(int $minimum_days, int $maximum_days, float $cost): Pricelist
    {
        $pricelist_range               = new PricelistRange();
        $pricelist_range->pricelist_id = $this->id;
        $pricelist_range->minimum_days = $minimum_days;
        $pricelist_range->maximum_days = $maximum_days;
        $pricelist_range->cost         = $cost;

        $pricelist_range->save();

        return $this;
    }

    public function getCostFromDates(Carbon $date_from, Carbon $date_to): float
    {
        $days = self::getNumberOfDaysFromDates($date_from, $date_to);

        return $this->getCostFromNumberOfDays($days);
    }

    public static function getNumberOfDaysFromDates(Carbon $date_from, Carbon $date_to): int
    {
        $date_from->setTime(0, 0, 0, 0);
        $date_to->setTime(0, 0, 0, 0);

        return
            $date_from->lessThan($date_to)
                ? $date_to->diffInDays($date_from)
                : 0;
    }

    public function getCostFromNumberOfDays(int $days): float
    {
        $extra_day_cost  = $this->extra_day_cost;
        $pricelist_range = PricelistRange::where([
            ['pricelist_id', '=', $this->id],
            ['minimum_days', '<=', $days],
            ['maximum_days', '>=', $days],
        ])->orderBy('id', 'desc')->first();

        $day_cost = $pricelist_range ? $pricelist_range->cost : $extra_day_cost;
        $result   = $days * $day_cost;

        return $result;
    }
}
