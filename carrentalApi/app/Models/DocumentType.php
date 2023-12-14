<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentTypeProfile;
use App\Filters\DocumentTypeFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 */
class DocumentType extends Model
{
    protected $fillable = [
        'id',
    ];

    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new DocumentTypeFilter($request))->filter($builder);
    }

    public function getProfileByLanguageId(string $language_id = 'el')
    {
        return DocumentTypeProfile::where('type_id', $this->id)
            ->where('language_id', $language_id)
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {

       return $this->hasMany(DocumentTypeProfile::class, 'type_id', 'id');
    }
}
