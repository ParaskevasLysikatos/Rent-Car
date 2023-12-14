<?php

namespace App\Models;

use App\Filters\OptionsFilter;
use App\Traits\ModelHasProfileDescriptionAttributeTrait;
use App\Traits\ModelHasProfileTitleAttributeTrait;
use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * \App\Option
 *
 * @property int          $id
 * @property string       $created_at
 * @property string       $updated_at
 * @property string       $deleted_at
 * @property string       $slug
 * @property float|null   $cost
 * @property float|null   $cost_daily
 * @property float|null   $cost_max
 * @property int|null     $items_max
 * @property boolean|null $active_daily_cost
 * @property boolean|null $default_on
 * @property string|null  $icon
 * @property int $order
 * @property-read mixed $profile_title
 * @property string $option_type
 */
class Option extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;
    use ModelHasProfileDescriptionAttributeTrait;

    const ALAILABLE_TYPES = [
        'extras',
        'damages',
        'fuel',
        'insurances',
        'rental_charges',
        'rental_discount',
        'surcharges',
        'taxes',
        'transport',
        'unspecified'
    ];

    protected $fillable = [
        'id',
    ];

    protected $casts = [
        'cost'       => 'float',
        'cost_daily' => 'float',
        'cost_max'   => 'float',
    ];


    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new OptionsFilter($request))->filter($builder);
    }

    public function types() {
        return $this->morphedByMany(Type::class, 'option_link');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(OptionProfile::class, 'option_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return OptionProfile::where('option_id', $this->id)
                            ->where('language_id', $language_id)
                            ->first();
    }
}
