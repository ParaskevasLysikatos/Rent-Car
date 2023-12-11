<?php

namespace App\Models;

use App\Filters\RentalFilter;
use App\Traits\ModelAffectsVehicleStatusInterface;
use App\Traits\ModelAffectsVehicleStatusTrait;
use App\Traits\ModelAffectsVehicleTrait;
use App\Traits\ModelBelongsToTypeTrait;
use App\Traits\ModelBelongsToUserTrait;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasCommentsTrait;
use App\Traits\ModelHasDocumentsTrait;
use App\Traits\ModelHasDriversTrait;
use App\Traits\ModelHasModificationNumberTrait;
use App\Traits\ModelHasPayableOptionsTrait;
use App\Traits\ModelHasPaymentsTrait;
use App\Traits\ModelHasPrefixObserverTrait;
use App\Traits\ModelHasPrintingsTrait;
use App\Traits\ModelHasTagsTrait;
use App\Traits\ModelIsSortableTrait;
use App\Traits\ReservationsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property string|null $completed_at
 * @property string      $deleted_at
 * @property int|null    $user_id
 * @property int|null    $type_id
 * @property int|null    $charge_type_id
 * @property int|null    $company_id
 * @property int|null    $vehicle_id
 * @property int|null    $agent_id
 * @property int|null    $brand_id
 * @property int|null    $source_id
 * @property int|null    $contact_information_id
 * @property string      $status
 * @property int         $duration
 * @property float       $rate
 * @property float|null  $extension_rate
 * @property boolean     $may_extend
 * @property int|null    $distance
 * @property float       $distance_rate
 * @property string      $checkout_datetime
 * @property int         $checkout_station_id
 * @property int         $checkout_place_id
 * @property float       $checkout_station_fee
 * @property string|null $checkout_comments
 * @property string      $checkin_datetime
 * @property int         $checkin_station_id
 * @property int         $checkin_place_id
 * @property float       $checkin_station_fee
 * @property string|null $checkin_comments
 * @property string|null $comments
 * @property float       $transport_fee
 * @property float       $insurance_fee
 * @property float       $options_fee
 * @property float       $fuel_fee
 * @property float       $subcharges_fee
 * @property float       $discount
 * @property float       $voucher
 * @property float       $vat
 * @property float       $total_net
 * @property float       $total
 * @property float       $total_paid
 * @property float       $balance
 */
class Rental extends Model implements ModelAffectsVehicleStatusInterface
{
    use ModelAffectsVehicleStatusTrait;
    use ModelAffectsVehicleTrait;
    use ModelBelongsToUserTrait;
    use ModelBelongsToTypeTrait;
    use ModelHasCommentsTrait;
   // use ModelHasDocumentsTrait; //to not activate
    use ModelCreatesActionLogsTrait;
    use ModelHasPaymentsTrait;
    use ModelHasDriversTrait;
    // use ModelHasOptionsTrait;
    use ModelHasPayableOptionsTrait;
    use SoftDeletes;
    use Notifiable;
    use ModelHasTagsTrait;
    use ModelHasModificationNumberTrait;
    use ModelHasPrefixObserverTrait;
    use ReservationsTrait;
    use ModelHasPrintingsTrait;
    use ModelIsSortableTrait;

    protected $fillable = ['id'];

    protected $casts = [
        'rate'                 => 'float',
        'distance_rate'        => 'float',
        'extension_rate'       => 'float',
        'checkout_station_fee' => 'float',
        'checkin_station_fee'  => 'float',
        'transport_fee'        => 'float',
        'insurance_fee'        => 'float',
        'options_fee'          => 'float',
        'fuel_fee'             => 'float',
        'rental_fee'           => 'float',
        'subcharges_fee'       => 'float',
        'discount'             => 'float',
        'voucher'              => 'float',
        'total'                => 'float',
        'total_net'            => 'float',
        'total_paid'           => 'float',
        'vat'                  => 'float',
        'vat_fee'              => 'float',
        'balance'              => 'float',
        'checkout_fuel_level'  => 'integer'
    ];

    public function setCheckoutFuelLevelAttribute($value)
    {
        $this->attributes['checkout_fuel_level'] = intval($value);
    }

    public function setCheckinFuelLevelAttribute($value)
    {
        $this->attributes['checkin_fuel_level'] = $value ? intval($value) : null;
    }

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_PRE_CHECKED_IN,
            self::STATUS_CHECKED_IN,
            self::STATUS_CANCELLED,
            self::STATUS_CLOSED,
        ];
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new RentalFilter($request))->filter($builder);
    }

    public function getStationField()
    {
        return Station::RENTAL_NUMBER;
    }

    public function getPrefix()
    {
        return config('preferences.rental_prefix');
    }

    public static function getPrefixField()
    {
        return 'checkout_station_id';
    }

    public function getPrefixName()
    {
        return $this->checkout_station->code;
    }

    public function printingView(): string
    {
        return 'rental-agreement';
    }

    public function getDocumentsPath()
    {
        return 'cars/car-'.$this->vehicle_id.'/rental/';
    }

    public function getInitialFileName()
    {
        return 'rental-'.$this->id;;
    }

    public function setCheckoutCommentsAttribute($value)
    {
        $value = strval($value);
        $value = trim($value);

        if (empty($value)) {
            $value = NULL;
        }

        $this->attributes['checkout_comments'] = $value;
    }

    public function setCheckinCommentsAttribute($value)
    {
        $value = strval($value);
        $value = trim($value);

        if (empty($value)) {
            $value = NULL;
        }

        $this->attributes['checkin_comments'] = $value;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function charge_type()
    {
        return $this->belongsTo(Type::class, 'charge_type_id', 'id');
    }

    public function program()//v2
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function customer() {
        return $this->driver();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sub_account()
    {
        return $this->morphTo();
    }

    public function getSubAccountNormalizedAttribute() {
        $subaccount = $this->sub_account;
        if ($subaccount) {
            $subaccount->name = $subaccount->name ?? $subaccount->full_name;
            $subaccount->account_id = $subaccount->id;
            $subaccount->model = $this->sub_account_type;
        }
        return $subaccount;
    }

    public function getCustomerTextAttribute() {
        return $this->driver->full_name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(BookingSource::class, 'source_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking_source()
    {
        return $this->source();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact_information()
    {
        return $this->belongsTo(ContactInformation::class, 'contact_information_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkout_station()
    {
        return $this->belongsTo(Station::class, 'checkout_station_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkin_station()
    {
        return $this->belongsTo(Station::class, 'checkin_station_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkout_place() {
        return $this->belongsTo(Place::class, 'checkout_place_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkin_place() {
        return $this->belongsTo(Place::class, 'checkin_place_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkout_driver() {
        return $this->belongsTo(Driver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkin_driver() {
        return $this->belongsTo(Driver::class);
    }

    public static function calculateNetTotal(int $duration, float $rate, float $discount = 0, float $voucher = 0, float $transport_fee = 0, float $insurance_fee = 0, float $options_fee = 0, float $fuel_fee = 0, float $subcharges_fee = 0): float
    {
        $rate_total     = $duration * $rate;
        $discount_total = $rate_total * $discount;

        $result = $rate_total - $discount_total - $voucher + $transport_fee + $insurance_fee + $options_fee + $fuel_fee + $subcharges_fee;
        $result = round($result, 2);

        return $result;
    }

    public static function calculateTotal(float $net_total, float $vat = 0.24): float
    {
        $result = $net_total * (1 + $vat);
        $result = round($result, 2);

        return $result;
    }

    public function addExtaCharges($extra_charges) {
        $this->extra_charges = $extra_charges;
        $this->total += $extra_charges;
        $this->total_net = $this->total_net/(1 + $this->vat/100);
        $this->vat_fee = $this->total - $this->total_net;
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function transitions() {
        return $this->hasMany(Transition::class);
    }

    public function replace() {
        return $this->exchanges()->latest('id')->first();
    }

    public function replacedCar() {
        return $this->replace()->vehicle ?? null;
    }

    public function getSequenceNumberAttribute() {
        return $this->manual_agreement ?? $this->attributes['sequence_number'];
    }

    public function exchanges() {
        return $this->hasMany(VehicleExchange::class);
    }

    public function getKmDrivenAttribute() {
        $exchanges = $this->exchanges;
        $km = 0;
        foreach ($exchanges as $exchange) {
            if ($exchange->old_vehicle_rental_ci_km) {
                $km += $exchange->old_vehicle_rental_ci_km - $exchange->old_vehicle_rental_co_km;
            }
        }
        if ($this->checkin_km) {
            $km += $this->checkin_km - $this->checkout_km;
        }

        return $km;
    }
}
