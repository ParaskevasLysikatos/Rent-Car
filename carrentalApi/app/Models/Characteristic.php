<?php

namespace App;

use App\Filters\CharacteristicFilter;
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
class Characteristic extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;

    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new CharacteristicFilter($request))->filter($builder);
    }


    public function profiles()
    {
        return $this->hasMany(CharacteristicProfile::class, 'characteristic_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return CharacteristicProfile::where('characteristic_id', $this->id)
                                    ->where('language_id', $language_id)
                                    ->first();
    }
}