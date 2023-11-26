<?php

namespace App;

use App\Filters\PlaceFilter;
use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Model;
use App\Station;
use App\PlaceProfile;
use App\Traits\ModelHasProfileTitleAttributeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{

    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new PlaceFilter($request))->filter($builder);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }

    public function profiles()
    {
        return $this->hasMany(PlaceProfile::class, 'place_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return PlaceProfile::where('place_id', $this->id)
            ->where('language_id', $language_id)
            ->first();
    }
}