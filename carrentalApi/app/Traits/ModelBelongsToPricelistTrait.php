<?php

namespace App\Traits;

use App\Pricelist;

trait ModelBelongsToPricelistTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle()
    {
        return $this->belongsTo(Pricelist::class, 'pricelist_id', 'id');
    }
}
