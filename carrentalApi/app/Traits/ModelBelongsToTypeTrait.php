<?php

namespace App\Traits;

use App\Type;

trait ModelBelongsToTypeTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}
