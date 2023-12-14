<?php

namespace App\Models;

use App\Filters\CategoryFilter;
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
class Category extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasProfileTitleAttributeTrait;

    protected $fillable = [
        'id',
    ];

    protected $appends = [
        'profile_title'
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new CategoryFilter($request))->filter($builder);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(CategoryProfile::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function types()
    {
        return $this->hasMany(Type::class, 'category_id', 'id');
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return CategoryProfile::where('category_id', $this->id)
                              ->where('language_id', $language_id)
                              ->first();
    }
}
