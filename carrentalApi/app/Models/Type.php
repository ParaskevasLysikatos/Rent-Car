<?php

namespace App;

use App\Filters\TypeFilter;
use App\Traits\ModelHasImagesTrait;
use App\Traits\ModelHasOptionsTrait;
use App\Traits\ModelHasProfileTitleAttributeTrait;
use App\Traits\ModelHasSlugTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int         $id
 * @property int         $category_id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string      $deleted_at
 * @property string      $slug
 * @property string|null $icon
 */
class Type extends Model
{
    use SoftDeletes;
    use ModelHasSlugTrait;
    use ModelHasImagesTrait;
    use ModelHasOptionsTrait;
    use ModelHasProfileTitleAttributeTrait;

    protected $fillable = [
        'id',
    ];

    protected $appends = [
        'international_title'
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new TypeFilter($request))->filter($builder);
    }

    public function getImagesPath()
    {
        return 'types/type-'. $this->id . '/';
    }

    public function getInitialFileName()
    {
        return 'typeID';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getInternationalTitleAttribute() {
        return $this->category->profile_title.' - '.$this->international_code;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(TypeProfile::class, 'type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'type_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function characteristics2()
    {
        return $this->belongsToMany(
            Characteristic::class,
            'type_characteristics',
            'type_id',
            'characteristic_id',
            'id',
            'id'
        )->orderBy('ordering')
                    ->orderBy('id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function characteristics()
    {
        return $this->hasManyThrough(
            Characteristic::class,
            TypeCharacteristic::class,
            'type_id',
            'id',
            'id',
            'characteristic_id'
        )->orderBy('ordering')
        ->orderBy('id');
    }
    

    public function addCharacteristic(Characteristic $characteristic, int $ordering = 100): self
    {
        $type_characteristic                    = new TypeCharacteristic();
        $type_characteristic->type_id           = $this->id;
        $type_characteristic->characteristic_id = $characteristic->id;
        $type_characteristic->ordering          = $ordering;

        $type_characteristic->save();

        return $this;
    }

    public function deleteCharacteristics(): self
    {
        TypeCharacteristic::where('type_id', $this->id)->delete();
        return $this;
    }

    /**
     * @TODO remove
     */
    public function currentProfile()
    {
        return TypeProfile::where('type_id', $this->id)
                          ->where('language_id', app()->getLocale())
                          ->first();
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return TypeProfile::where('type_id', $this->id)
                          ->where('language_id', $language_id)
                          ->first();
    }
}
