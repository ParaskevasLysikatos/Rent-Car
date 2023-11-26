<?php

namespace App;

use App\Exceptions\InvalidStatusException;
use App\Exceptions\VehicleStatusInvalidStatusException;
use App\Traits\ModelBelongsToUserTrait;
use App\Traits\ModelBelongsToVehicleTrait;
use App\Traits\ModelHasStatusInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string|null $completed_at
 * @property int         $vehicle_id
 * @property int|null    $user_id
 * @property string      $status
 * @property int|null    $booking_id
 * @property int|null    $transition_id
 * @property int|null    $maintenance_id
 */
class VehicleStatus extends Model implements ModelHasStatusInterface
{
    use Notifiable;
    use ModelBelongsToUserTrait;
    use ModelBelongsToVehicleTrait;

    const STATUS_BOOKING     = 'booking';
    const STATUS_RENTAL      = 'rental';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_TRANSITION  = 'transition';

    public function complete() {
        if (!$this->complete_at) {
            $this->completed_at = now();
        }
        $this->save();
    }




    public function setStatusAttribute($value)
    {
        $value = Str::slug($value, '-');
        $value = strtolower($value);

        if (!in_array($value, self::getValidStatuses())) {
            throw new InvalidStatusException('Status ' . $value . ' is invalid.');
        }

        $this->attributes['status'] = $value;
    }

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_BOOKING,
            self::STATUS_RENTAL,
            self::STATUS_MAINTENANCE,
            self::STATUS_TRANSITION,
        ];
    }

}
