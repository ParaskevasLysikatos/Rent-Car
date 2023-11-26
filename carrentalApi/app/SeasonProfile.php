<?php

namespace App;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string      $language_id
 * @property int         $season_id
 * @property string      $title
 * @property string|null $description
 */
class SeasonProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'season_id', 'language_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }

}
