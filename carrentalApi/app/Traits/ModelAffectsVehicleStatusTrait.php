<?php


namespace App\Traits;

use App\Exceptions\InvalidStatusException;
use App\Models\Vehicle;
use App\Models\VehicleStatus;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait ModelAffectsVehicleStatusTrait
{
    public static function getValidStatuses(): array
    {
        return [
            ModelAffectsVehicleStatusInterface::STATUS_PENDING,
            ModelAffectsVehicleStatusInterface::STATUS_BOOKING,
            ModelAffectsVehicleStatusInterface::STATUS_RENTAL,
            ModelAffectsVehicleStatusInterface::STATUS_ACTIVE,
            ModelAffectsVehicleStatusInterface::STATUS_PRE_CHECKED_IN,
            ModelAffectsVehicleStatusInterface::STATUS_CHECKED_IN,
            ModelAffectsVehicleStatusInterface::STATUS_COMPLETED,
            ModelAffectsVehicleStatusInterface::STATUS_CANCELLED,
            ModelAffectsVehicleStatusInterface::STATUS_CLOSED,
            ModelAffectsVehicleStatusInterface::STATUS_CONFIRMED,
            ModelAffectsVehicleStatusInterface::STATUS_SOLD,
        ];
    }

    public static function getStatusTexts(): array
    {
        return [
            ModelAffectsVehicleStatusInterface::STATUS_PENDING => __('Αναμονή'),
            ModelAffectsVehicleStatusInterface::STATUS_BOOKING => __('Κράτηση'),
            ModelAffectsVehicleStatusInterface::STATUS_RENTAL => __('Ενοικίαση'),
            ModelAffectsVehicleStatusInterface::STATUS_ACTIVE => __('Ενεργή'),
            ModelAffectsVehicleStatusInterface::STATUS_PRE_CHECKED_IN => __('Pre Check'),
            ModelAffectsVehicleStatusInterface::STATUS_CHECKED_IN => __('Check-in'),
            ModelAffectsVehicleStatusInterface::STATUS_COMPLETED => __('Ολοκληρωμένη'),
            ModelAffectsVehicleStatusInterface::STATUS_CANCELLED => __('Ακυρωμένη'),
            ModelAffectsVehicleStatusInterface::STATUS_CLOSED => __('Κλειστό'),
            ModelAffectsVehicleStatusInterface::STATUS_CONFIRMED => __('Επιβεβαιωμένη'),
            ModelAffectsVehicleStatusInterface::STATUS_SOLD => __('Πουλήθηκε'),
        ];
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

    public function getStatusTextAttribute()
    {
        return self::getStatusTexts()[$this->status];
    }

    public function vehicle_status(): ?VehicleStatus
    {
        switch (static::class) {
            case "App\\Rental":
                $field = 'rental_id';

                break;

            case "App\\Maintenance":
                $field = 'maintenance_id';

                break;

            case "App\\Transition":
                $field = 'transition_id';

                break;

            default:
                return NULL;
        }

        return VehicleStatus::where([$field => $this->id])->orderBy('id', 'DESC')->first();
    }

    public function complete(Carbon $completed_at = NULL): ModelAffectsVehicleStatusInterface
    {
        if (is_null($completed_at)) {
            $completed_at = new Carbon();
        }

        $completed_at   = $completed_at->format('Y-m-d H:i:s');
        $vehicle_status = $this->vehicle_status();

        if ($vehicle_status && is_null($vehicle_status->completed_at)) {
            $vehicle_status->completed_at = $completed_at;
            $vehicle_status->save();

            $vehicle = $vehicle_status->vehicle()->first();

            if ($vehicle) {
                if (static::class == "App\\Rental") {
                    $vehicle->station_id = $this->checkin_station_id;
                }

                if (static::class == "App\\Transition") {
                    $vehicle->station_id = $this->station_id_to;
                }

                $vehicle->status = Vehicle::STATUS_AVAILABLE;
                $vehicle->save();
            }
        }

        $this->completed_at = $completed_at;
        $this->save();

        return $this;
    }
}
