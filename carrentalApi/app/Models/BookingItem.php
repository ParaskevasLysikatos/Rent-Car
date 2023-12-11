<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BookingItem
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $option_id
 * @property string $payer_type
 * @property int $payer_id
 * @property float $rate
 * @property float $net
 * @property float $gross
 * @property int $quantity
 */
class BookingItem extends Model
{
    public $fillable = ['option_id', 'rental_id', 'booking_id', 'quote_id'];

    public function option() {
        return $this->belongsTo(Option::class);
    }

    public function payer() {
        return $this->morphTo();
    }
}
