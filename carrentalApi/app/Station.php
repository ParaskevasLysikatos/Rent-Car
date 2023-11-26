<?php

namespace App;

use App\Filters\StationFilter;
use App\Traits\ModelHasProfileTitleAttributeTrait;
use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $slug
 * @property int    $location_id
 * @property string $latitude
 * @property string $longitude
 */
class Station extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;

    const QUOTE_NUMBER = 'quote_number';
    const BOOKING_NUMBER = 'booking_number';
    const RENTAL_NUMBER = 'rental_number';
    const INVOICE_NUMBER = 'invoice_number';
    const FOREIGN_NUMBER = 'foreign_number';
    const RECEIPT_NUMBER = 'receipt_number';
    const PAYMENT_NUMBER = 'payment_number';
    const REFUND_NUMBER = 'refund_number';
    const PRE_AUTH_NUMBER = 'pre_auth_number';
    const REFUND_PRE_AUTH_NUMBER = 'refund_pre_auth_number';

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new StationFilter($request))->filter($builder);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function places() {
        return $this->belongsToMany(Place::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(StationProfile::class, 'station_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return StationProfile::where('station_id', $this->id)
                             ->where('language_id', $language_id)
                             ->first();
    }
}