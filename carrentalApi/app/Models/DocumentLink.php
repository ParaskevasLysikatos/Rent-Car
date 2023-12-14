<?php

namespace App\Models;

use App\Traits\ModelBelongsToDocumentTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int      $id
 * @property int      $document_id
 * @property int      $document_link_id
 * @property int      $document_link_type
 */
class DocumentLink extends Model
{
    use ModelBelongsToDocumentTrait;

    public $timestamps = false;
}
