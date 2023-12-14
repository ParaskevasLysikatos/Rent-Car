<?php

namespace App\Models;

use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

class BookingSourceProfile extends Model
{
    use ModelIsLocalizedProfileTrait;
    protected $fillable = [
        'booking_source_id', 'language_id',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking_source()
    {
        return $this->belongsTo(BookingSource::class, 'booking_source_id', 'id');
    }
}
