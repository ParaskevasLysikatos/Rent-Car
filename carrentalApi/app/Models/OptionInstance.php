<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $option_id
 * @property string $process_type
 * @property int $process_id
 * @property string $client_type
 * @property int $client_id
 * @property float $rate
 * @property int $units
 * @property float $net
 * @property float $vat
 * @property float $total
 */
class OptionInstance extends Model
{
    protected $fillable = [
        'option_id', 'process_id', 'process_type', 'client_id', 'client_type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function process() {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function client() {
        return $this->morphTo();
    }
}
