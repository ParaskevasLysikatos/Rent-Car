<?php

namespace App;

use App\Filters\LocationFilter;
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
 * @property string $latitude
 * @property string $longitude
 */
class Location extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new LocationFilter($request))->filter($builder);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(LocationProfile::class, 'location_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stations()
    {
        return $this->hasMany(Station::class, 'location_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return LocationProfile::where('location_id', $this->id)
                              ->where('language_id', $language_id)
                              ->first();
    }

}