<?php

namespace App;

use App\Filters\UserRoleFilter;
use App\Traits\ModelHasTitleAndDescriptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string      $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string      $deleted_at
 * @property string      $title
 * @property string|null $description
 */
class UserRole extends Model
{
    use ModelHasTitleAndDescriptionTrait;

    public $incrementing = FALSE;

    protected $fillable = [
        'id', 'title', 'description',
    ];


    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new UserRoleFilter($request))->filter($builder);
    }

    public function setIdAttribute($value)
    {
        $value = strval($value);
        $value = trim($value);
        $value = Str::slug($value, '-');

        $this->attributes['id'] = $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}