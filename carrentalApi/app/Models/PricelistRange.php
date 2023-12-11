<?php

namespace App\Models;

use App\Traits\ModelBelongsToPricelistTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 * @property int    $pricelist_id
 * @property string $title
 * @property float  $cost
 * @property int    $minimum_days
 * @property int    $maximum_days
 */
class PricelistRange extends Model
{
    use ModelBelongsToPricelistTrait;
    use Notifiable;

    protected $casts = [
        'cost' => 'float',
    ];

}
