<?php

namespace App;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $characteristic_id
 * @property string      $title
 * @property string|null $description
 *
 */
class CharacteristicProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'characteristic_id', 'language_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class, 'characteristic_id', 'id');
    }
}
