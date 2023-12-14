<?php

namespace App\Models;

use App\Traits\ModelBelongsToTypeTrait;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property int $id
 * @property int $type_id
 * @property int $characteristic_id
 * @property int $ordering
 *
 * @package App
 */
class TypeCharacteristic extends Model
{
    use ModelBelongsToTypeTrait;

    public $timestamps = FALSE;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class, 'id', 'characteristic_id');
    }
}
