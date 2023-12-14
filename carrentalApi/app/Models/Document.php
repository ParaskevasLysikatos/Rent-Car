<?php

namespace App\Models;

use App\Filters\DocumentFilter;
use App\Traits\ModelHasCommentsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int      $id
 * @property string   $created_at
 * @property string   $updated_at
 * @property string   $deleted_at
 * @property int|null $type_id
 * @property int|null $user_id
 * @property string   $path
 * @property string|null $mime_type
 * @property string|null $md5
 * @property string|null $comments
 */
class Document extends Model
{
    use SoftDeletes;
    use ModelHasCommentsTrait;

    const DOCUMENT_PRINT_TYPE = 'print';

    protected $fillable = [
        'id',
    ];


    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new DocumentFilter($request))->filter($builder);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document_type()
    {
        return $this->type();
    }
}
