<?php

namespace App;

use App\Traits\ModelBelongsToVehicleTrait;
use App\Traits\ModelIsLocalizedProfileTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $vehicle_id
 * @property string      $title
 * @property string|null $description
 * @property string|null $color_exterior
 * @property string|null $color_interior
 */
class VehicleProfile extends Model
{
    use ModelIsLocalizedProfileTrait;
    use ModelBelongsToVehicleTrait;

    protected $fillable = [
        'vehicle_id',
    ];
}
