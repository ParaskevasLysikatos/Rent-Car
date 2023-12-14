<?php

namespace App\Models;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $station_id
 * @property string      $title
 * @property string|null $description
 */
class StationProfile extends Model
{
    use ModelIsLocalizedProfileTrait;

    protected $fillable = [
        'station_id', 'language_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }
}
