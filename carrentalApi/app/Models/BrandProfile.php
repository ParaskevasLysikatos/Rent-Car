<?php

namespace App;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $brand_id
 * @property string      $title
 * @property string|null $description
 */
class BrandProfile extends Model
{
    use ModelIsLocalizedProfileTrait;
    protected $fillable = [
        'brand_id', 'language_id',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
