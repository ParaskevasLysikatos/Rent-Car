<?php

declare(strict_types=1);

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VehicleReservations extends Model
{
    protected $table = 'vehicle_reservations';

    public static function getReservedVehiclesQueryBuilder(DateTime $checkout_date, DateTime $checkin_date, int $exclude_id = null, string $exclude_type = null): Builder
    {
        $query = VehicleReservations::where(function($where_query) use($checkout_date, $checkin_date) {
            $where_query->where(
                function ($subquery) use ($checkout_date, $checkin_date) {
                    $subquery->where('checkout_datetime', '<=', $checkout_date)
                        ->where('checkin_datetime', '>=', $checkout_date);
                }
            )
            ->orWhere(
                function ($subquery) use ($checkout_date, $checkin_date) {
                    $subquery->where('checkout_datetime', '<=', $checkin_date)
                        ->where('checkin_datetime', '>=', $checkin_date);
                }
            )
            ->orWhere(
                function ($subquery) use ($checkout_date, $checkin_date) {
                    $subquery->where('checkout_datetime', '>=', $checkout_date)
                        ->where('checkin_datetime', '<=', $checkin_date);
                }
            );
        });

        if ($exclude_id !== null && $exclude_type !== null) {
            $query = $query->where(function($where_query) use ($exclude_id, $exclude_type) {
                $where_query->where('id', '!=', $exclude_id)->orWhere('type', '!=', $exclude_type);
            });
        }

        return $query->select('vehicle_id')->distinct();
    }

    public static function getReservedVehicles(DateTime $checkout_date, DateTime $checkin_date, int $exclude_id = null, string $exclude_type = null): array
    {
        $query = self::getReservedVehiclesQueryBuilder($checkout_date, $checkin_date, $exclude_id, $exclude_type);

        return $query->get()->map(
            function ($item) {
                return $item['vehicle_id'];
            }
        )->toArray();
    }

    public static function getReservedVehicleStatuses(DateTime $from, DateTime $to, int $vehicle_id) {
        $exclude_vehicle_ids = self::getReservedVehicles($from, $to);

        if (in_array($vehicle_id, $exclude_vehicle_ids)) {
            return self::where('vehicle_id', $vehicle_id)->get('type')->pluck('type');
        }

        return ['available'];
    }
}
