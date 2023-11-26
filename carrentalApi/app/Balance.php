<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $balance_datetime
 * @property float $debit
 * @property float $credit
 * @property string $client_type
 * @property int $client_id
 */
class Balance extends Model
{
    use SoftDeletes;

    public function client() {
        return $this->morphTo();
    }

    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}
