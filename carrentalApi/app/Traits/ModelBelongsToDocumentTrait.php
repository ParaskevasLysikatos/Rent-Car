<?php

namespace App\Traits;

use App\Models\Document;

trait ModelBelongsToDocumentTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
