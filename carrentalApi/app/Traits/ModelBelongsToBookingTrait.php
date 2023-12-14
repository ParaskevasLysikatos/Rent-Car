<?php

namespace App\Traits;

use App\Models\Booking;

trait ModelBelongsToBookingTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
}
