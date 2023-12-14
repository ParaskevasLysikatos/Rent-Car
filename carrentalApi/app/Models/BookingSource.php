<?php

namespace App\Models;

use App\Filters\BookingSourceFilter;
use App\Traits\ModelHasProfileDescriptionAttributeTrait;
use App\Traits\ModelHasProfileTitleAttributeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\BookingSource
 *
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $slug
 * @property int|null $program_id
 * @property int|null $brand_id
 * @property-read mixed $profile_description
 * @property-read mixed $profile_title
 * @property int|null $agent_id
 */
class BookingSource extends Model
{
    use SoftDeletes;
    use ModelHasProfileTitleAttributeTrait;
    use ModelHasProfileDescriptionAttributeTrait;

    public $fillable = ['id'];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new BookingSourceFilter($request))->filter($builder);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'source_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program() {
        return $this->belongsTo(Program::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent() {
        return $this->belongsTo(Agent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(BookingSourceProfile::class, 'booking_source_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return $this->profiles()->where('language_id', $language_id)->first();
    }
}
