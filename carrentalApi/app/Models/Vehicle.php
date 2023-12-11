<?php

namespace App;

use App\Exceptions\InvalidStatusException;
use App\Filters\VehicleFilter;
use App\Order\VehicleOrder;
use App\Traits\ModelBelongsToTypeTrait;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasDocumentsTrait;
use App\Traits\ModelHasImagesTrait;
use App\Traits\ModelHasStatusInterface;
use DateTime;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use stdClass;
use Illuminate\Support\Carbon;
/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string      $deleted_at
 * @property int         $type_id
 * @property string|null $status
 * @property string|null $status_id
 * @property int|null    $station_id
 * @property int|null    $km
 * @property int|null    $fuel_level
 * @property string      $vin
 * @property string      $make
 * @property string      $model
 * @property string|null $variant
 * @property string      $engine
 * @property string      $power
 * @property string      $drive_type
 * @property string      $transmission
 * @property int|null    $doors
 * @property int|null    $seats
 * @property string|null $euroclass
 * @property string|null $color_exterior
 * @property string|null $color_interior
 */
class Vehicle extends Model implements ModelHasStatusInterface
{
    use ModelBelongsToTypeTrait;
    use ModelCreatesActionLogsTrait;
    use ModelHasImagesTrait;
    use ModelHasDocumentsTrait;
    use VehicleOrder;
    use SoftDeletes;

    const STATUS_AVAILABLE = 'available';

    protected $fillable = [
        'id', 'vin',
    ];

    public function scopeFilter(Builder $builder, $request)
    {
        return (new VehicleFilter($request))->filter($builder);
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
        $statuses   = VehicleStatus::getValidStatuses();
        $statuses[] = self::STATUS_AVAILABLE;

        return $statuses;
    }

    public function getDocumentsPath()
    {
        return 'cars/car-'.$this->id.'/documents/';
    }

    public function getImagesPath() {
        return 'cars/car-'.$this->id.'/images/';
    }

    public function getInitialFileName() {
        return 'carID';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicle_statuses()
    {
        return $this->hasMany(VehicleStatus::class, 'vehicle_id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * @return \App\VehicleStatus|null
     */
    public function getActiveVehicleStatus()
    {
        return VehicleStatus::where(['vehicle_id' => $this->id, 'completed_at' => NULL])->orderBy('id', 'DESC')->first();
    }

    public function scopeIsActive($q) {
        return $q->where(function (Builder $enq) {
                    $enq->where('status', 'LIKE', Vehicle::STATUS_AVAILABLE)
                        ->orWhereNull('status');
                })
                ->where(function (Builder $enq) {
                        $enq->whereHas('vehicle_status', function(Builder $ennq) {
                            $ennq->where('slug', Status::STATUS_ACTIVE);
                        })->orWhereDoesntHave('vehicle_status');
                });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(VehicleProfile::class, 'vehicle_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicle_id', 'id');
    }

    public function booking()
    {
        return $this->bookings()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function license_plates()
    {
        return $this->hasMany(LicencePlate::class, 'vehicle_id', 'id')->orderBy('registration_date', 'DESC');
    }

    public function fees()
    {
        return $this->hasMany(PeriodicFee::class, 'vehicle_id', 'id')->orderBy('periodic_fee_type_id', 'ASC')->orderBy('date_expiration', 'DESC');
    }

    public function getKteoAttribute() {
        return $this->fees()->where('periodic_fee_type_id', 2)->first();
    }

    public function getInsuranceAttribute() {
        return $this->fees()->where('periodic_fee_type_id', 3)->first();
    }

    public function group() {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    /**
     * Return most recent licence plate
     *
     * @return String|null
     */
    public function getLicencePlateAttribute()
    {
        return $this->license_plates()->first()->licence_plate;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function service_visits()
    {
        return $this->hasMany(ServiceVisit::class, 'vehicle_id', 'id')->orderBy('date_start', 'DESC')->orderBy('created_at', 'DESC');
    }

    /**
     * Return most recent service visit
     *
     * @return \App\ServiceVisit|null
     */
    public function service_visit()
    {
        return $this->service_visits()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transitions()
    {
        return $this->hasMany(Transition::class, 'vehicle_id', 'id')->orderBy('co_datetime', 'DESC');
    }

    /**
     * Return most recent transition
     *
     * @return \App\Transition|null
     */
    public function transition()
    {
        return $this->transitions()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class)->orderBy('id', 'DESC');
    }

    /**
     *
     * @return \App\Rental
     */
    public function rental()
    {
        return $this->rentals()->first();
    }

    public function fuel_type() {
        return $this->belongsTo(FuelTypes::class, 'fuel_type_id', 'id');
    }

    public function color_type() {
        return $this->belongsTo(ColorTypes::class, 'color_type_id', 'id');
    }

    public function getWholeModelAttribute() {
        return $this->make." ".$this->model;
    }

    public function getLatestRelatedRecord($related) {
        $transition = $this->transition();
        $service_visit = $this->service_visit();
        $rental = $this->rental();
        $rental_date = $rental ? $rental->checkin_datetime : 0;
        $transition_date = $transition ? $transition->co_datetime : 0;
        $service_visit_date = $service_visit ? $service_visit->date_start : 0;
        switch ($related) {
            case ServiceVisit::class:
                if ($service_visit_date >= $transition_date && $service_visit_date >= $rental_date) {
                    return $service_visit;
                }
                break;
            case Transition::class:
                if ($transition_date >= $service_visit_date && $transition_date >= $rental_date) {
                    return $transition;
                }
                break;
            case Rental::class:
                if ($rental_date >= $transition_date && $rental_date >= $service_visit_date) {
                    return $rental;
                }
                break;
        }
        $obj = new stdClass();
        $obj->id = null;
        return $obj;
    }

    public function getProfileByLanguageId(string $language_id)
    {
        return VehicleProfile::where('vehicle_id', $this->id)
            ->where('language_id', $language_id)
            ->first();
    }

    public function getPlate()
    {
        return LicencePlate::where('vehicle_id', $this->id)
            ->orderBy('registration_date', 'desc')->first();
    }

    public function getLatestKM()
    {
        $sv = ServiceVisit::where('vehicle_id', $this->id)
            ->orderBy('created_at', 'DESC')->first();
        $t  = Transition::where('vehicle_id', $this->id)
            ->where('ci_km', '!=', null)
            ->where('ci_fuel_level', '!=', null)
            ->orderBy('created_at', 'DESC')->first();
        if ($sv === null && $t === null) {
            return 0;
        }

        if ($sv === null && $t !== null) {
            return $t->ci_km;
        }

        if ($sv !== null && $t === null) {
            return $sv->km;
        }

        if ($sv !== null && $t !== null) {
            if ($sv->created_at > $t->created_at) {
                return $sv->km;
            } else {
                return $t->ci_km;
            }
        }

        return 0;
    }

    public function getLatestFuel()
    {
        $sv = ServiceVisit::where('vehicle_id', $this->id)
            ->orderBy('created_at', 'DESC')->first();
        $t  = Transition::where('vehicle_id', $this->id)
            ->where('ci_km', '!=', null)
            ->where('ci_fuel_level', '!=', null)
            ->orderBy('created_at', 'DESC')->first();
        if ($sv === null && $t === null) {
            return 0;
        }

        if ($sv === null && $t !== null) {
            return $t->ci_fuel_level;
        }

        if ($sv !== null && $t === null) {
            return $sv->fuel_level;
        }

        if ($sv !== null && $t !== null) {
            if ($sv->created_at > $t->created_at) {
                return $sv->fuel_level;
            } else {
                return $t->ci_fuel_level;
            }
        }

        return 0;
    }

    public function getKM()
    {
        $visit = ServiceVisit::where('vehicle_id', $this->id)
            ->orderBy('created_at', 'DESC')->first();
        if ($visit === null) {
            return __('Άγνωστο');
        } else {
            return $visit->km;
        }
    }

    public function get_vehicle_status(DateTime $date) {
        return VehicleReservations::getReservedVehicleStatuses($date, $date, $this->id)[0];
    }

    public static function getSearchQuery(
        string $term = null,
        string $language_id = 'el',
        int $group_id = null,
        array $exclude_vehicle_ids = []
    ): Builder {
        if (is_string($term)) {
            $term = trim($term);
            if (strlen($term) < 2) {
                $term = null;
            }
        }

        $exclude_vehicle_ids = array_map(
            function ($item) {
                return is_int($item) || ctype_digit($item) ? (int)$item : false;
            },
            $exclude_vehicle_ids
        );

        $exclude_vehicle_ids = array_unique($exclude_vehicle_ids);
        $exclude_vehicle_ids = array_filter($exclude_vehicle_ids);
        $exclude_vehicle_ids = array_values($exclude_vehicle_ids);

        $query = Vehicle::query();

        if (!empty($term)) {
            $query->where(
                function ($query_term) use ($term, $language_id) {
                    $query_term->where(DB::raw('CONCAT(make, " ", model)'), 'like', '%' . $term . '%')
                        ->orWhere('vin', 'like', '%' . $term . '%')
                        ->orWhere('id', '=', $term);

                    $query_term->orWhereHas(
                        'license_plates',
                        function ($query_plates) use ($term) {
                            $query_plates->where("licence_plate", "like", "%" . $term . "%");
                        }
                    );

                    $query_term->orWhereHas(
                        'profiles',
                        function ($query_profiles) use ($term, $language_id) {
                            $query_profiles->where('title', 'like', '%' . $term . '%');
                            $query_profiles->where('language_id', $language_id);
                        }
                    );
                }
            );
        }

        if ($group_id !== null && $group_id > 0) {
            $query->whereHas(
                'group',
                function ($subquery) use ($group_id) {
                    $subquery->where('id', $group_id);
                }
            );

            $query->with('group');
        }

        if (!empty($exclude_vehicle_ids)) {
            $query->whereKeyNot($exclude_vehicle_ids);
        }

        return $query;
    }


}
