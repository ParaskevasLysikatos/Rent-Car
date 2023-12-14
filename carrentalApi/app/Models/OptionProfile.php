<?php

namespace App\Models;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $option_id
 * @property string      $title
 * @property string|null $description
 */
class OptionProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'option_id', 'language_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id', 'id');
    }
}
