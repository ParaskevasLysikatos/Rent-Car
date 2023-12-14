<?php

namespace App\Models;

use App\Filters\BrandFilter;
use App\Traits\ModelHasProfileTitleAttributeTrait;
use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string      $deleted_at
 * @property string      $slug
 * @property string|null $icon
 */
class Brand extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new BrandFilter($request))->filter($builder);
    }

    public function getProfileByLanguageId(string $language_id = 'el')
    {
        return BrandProfile::where('brand_id', $this->id)
            ->where('language_id', $language_id)
            ->first();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(BrandProfile::class, 'brand_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'brand_id', 'id');
    }

    public function printing_forms()
    {
        return $this->hasMany(PrintingFormsColor::class);
    }
}
