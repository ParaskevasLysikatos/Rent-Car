<?php


namespace App\Traits;

use App\Models\VehicleStatus;
use Carbon\Carbon;

interface ModelAffectsVehicleStatusInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_BOOKING = 'booking';
    const STATUS_RENTAL = 'rental';
    const STATUS_ACTIVE = 'active';
    const STATUS_CHECKED_IN = 'check-in';
    const STATUS_PRE_CHECKED_IN = 'pre-check';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CLOSED = 'closed';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SOLD = 'sold';

    public function vehicle_status(): ?VehicleStatus;

    public function complete(Carbon $completed_at = NULL): ModelAffectsVehicleStatusInterface;
}
