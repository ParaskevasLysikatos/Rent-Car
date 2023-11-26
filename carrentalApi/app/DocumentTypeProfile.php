<?php

namespace App;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $type_id
 * @property string      $title
 * @property string|null $description
 */
class DocumentTypeProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'type_id', 'language_id',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'type_id', 'id');
    }
}
