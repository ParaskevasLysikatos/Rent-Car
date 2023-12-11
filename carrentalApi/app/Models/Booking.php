<?php

namespace App\Models;

use App\Filters\BookingFilter;
use App\Traits\ModelAffectsVehicleStatusInterface;
use App\Traits\ModelAffectsVehicleStatusTrait;
use App\Traits\ModelBelongsToTypeTrait;
use App\Traits\ModelBelongsToUserTrait;
use App\Traits\ModelBelongsToVehicleTrait;
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
class Booking extends Model implements ModelAffectsVehicleStatusInterface
{
    use ModelAffectsVehicleStatusTrait;
    use ModelBelongsToUserTrait;
    use ModelBelongsToVehicleTrait;
    use ModelBelongsToTypeTrait;
    use ModelHasCommentsTrait;
    //use ModelHasDocumentsTrait; //do not activate!!
    use ModelCreatesActionLogsTrait;
    use ModelHasPaymentsTrait;
    use ModelHasDriversTrait;
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
        'extension_rate'       => 'float',
        'distance_rate'        => 'float',
        'checkout_station_fee' => 'float',
        'checkin_station_fee'  => 'float',
        'transport_fee'        => 'float',
        'insurance_fee'        => 'float',
        'options_fee'          => 'float',
        'fuel_fee'             => 'float',
        'subcharges_fee'       => 'float',
        'discount'             => 'float',
        'voucher'              => 'float',
        'total'                => 'float',
        'total_net'            => 'float',
        'total_paid'           => 'float',
        'vat'                  => 'float',
        'balance'              => 'float',
        'vat_included'         => 'boolean'
    ];

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_RENTAL,
            // self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_SOLD,
        ];
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new BookingFilter($request))->filter($builder);
    }

    public function getStationField()
    {
        return Station::BOOKING_NUMBER;
    }

    public function rental_chargesBasic() // used in printing html file
    {
        return $this->options()->where('slug', 'rental');
    }

    public function getPrefix()
    {
        return config('preferences.booking_prefix');
    }

    public static function getPrefixField()
    {
        return 'checkout_station_id';
    }

    public function getPrefixName()
    {
        return $this->checkout_station->code;
    }

    public function getDocumentsPath()
    {
        return 'cars/car-'.$this->vehicle_id.'/bookings/';
    }

    public function getInitialFileName()
    {
        return 'booking-'.$this->id;
    }

    public function printingView(): string
    {
        return 'booking-agreement';
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
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function charge_type()
    {
        return $this->belongsTo(Type::class, 'charge_type_id', 'id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->morphTo();
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
    public function checkout_place()
    {
        return $this->belongsTo(Place::class, 'checkout_place_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkin_place()
    {
        return $this->belongsTo(Place::class, 'checkin_place_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rental()
    {
        return $this->hasOne(Rental::class);
    }

    public function program() {
        return $this->belongsTo(Program::class);
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

    public function quote() {
        return $this->belongsTo(Quote::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
