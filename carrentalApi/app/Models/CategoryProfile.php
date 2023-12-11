<?php

namespace App;

use App\Traits\ModelBelongsToLanguageTrait;
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
class CategoryProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'category_id', 'language_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
