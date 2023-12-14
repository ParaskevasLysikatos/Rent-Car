<?php

namespace App\Traits;

use App\Models\Language;

trait ModelBelongsToLanguageTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
