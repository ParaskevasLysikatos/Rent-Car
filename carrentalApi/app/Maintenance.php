<?php

namespace App;

use App\Traits\ModelAffectsVehicleStatusInterface;
use App\Traits\ModelAffectsVehicleStatusTrait;
use App\Traits\ModelBelongsToUserTrait;
use App\Traits\ModelBelongsToVehicleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string|null $completed_at
 * @property string      $deleted_at
 * @property int         $user_id
 * @property int         $vehicle_id
 * @property string      $type
 */
class Maintenance extends Model implements ModelAffectsVehicleStatusInterface
{
    use ModelAffectsVehicleStatusTrait;
    use ModelBelongsToUserTrait;
    use ModelBelongsToVehicleTrait;
    use SoftDeletes;
    use Notifiable;
}
