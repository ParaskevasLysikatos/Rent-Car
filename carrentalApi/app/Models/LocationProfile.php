<?php

namespace App;

use App\Traits\ModelBelongsToLanguageTrait;
use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 * @property int    $language_id
 * @property int    $location_id
 * @property string $title
 */
class LocationProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'location_id', 'language_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
