<?php

namespace App;

use App\Traits\ModelBelongsToTypeTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $type_id
 * @property int $option_id
 * @property int $ordering
 */
class TypeOption extends Model
{
    use ModelBelongsToTypeTrait;

    public $timestamps = FALSE;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo(Option::class, 'id', 'option_id');
    }
}
