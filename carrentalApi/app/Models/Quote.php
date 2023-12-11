<?php

namespace App\Models;

use App\Filters\QuoteFilter;
use App\Traits\ModelHasTagsTrait;
use App\Traits\ModelHasDriversTrait;
use App\Traits\ModelHasCommentsTrait;
use App\Traits\ModelHasDocumentsTrait;
use App\Traits\ModelBelongsToTypeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\ModelBelongsToVehicleTrait;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasPayableOptionsTrait;
use App\Traits\ModelHasPrefixObserverTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ModelHasModificationNumberTrait;
use App\Traits\ModelHasPrintingsTrait;
use App\Traits\ModelIsSortableTrait;
use App\Traits\ReservationsTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Quote
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $customer_text
 * @property int|null $booking_id
 * @property int $user_id
 * @property int|null $company_id
 * @property int|null $type_id
 * @property int|null $brand_id
 * @property int|null $agent_id
 * @property int|null $source_id
 * @property int|null $program_id
 * @property int|null $charge_type_id
 * @property string $status
 * @property int $duration
 * @property float $rate
 * @property float $discount_percentage
 * @property string $checkout_datetime
 * @property int $checkout_station_id
 * @property int|null $checkout_place_id
 * @property float $checkout_station_fee
 * @property string|null $checkout_comments
 * @property string $checkin_datetime
 * @property int $checkin_station_id
 * @property int|null $checkin_place_id
 * @property float $checkin_station_fee
 * @property string|null $checkin_comments
 * @property int $may_extend
 * @property int|null $estimated_km
 * @property string $valid_date
 * @property int $distance
 * @property float $distance_rate
 * @property float $transport_fee
 * @property float $insurance_fee
 * @property float $options_fee
 * @property float $fuel_fee
 * @property float $subcharges_fee
 * @property float $discount
 * @property float $voucher
 * @property float $total
 * @property float $total_net
 * @property float $total_paid
 * @property float $vat
 * @property float $balance
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property float|null $excess
 * @property float $rental_fee
 * @property string|null $sub_account_type
 * @property int|null $sub_account_id
 * @property string|null $phone
 * @property string|null $checkout_place_text
 * @property string|null $checkin_place_text
 * @property int $extra_day
 * @property float $vat_fee
 * @property float|null $extension_rate
 */
class Quote extends Model
{
   // use ModelHasDocumentsTrait; //to not activate!!
    use ModelHasCommentsTrait;
    use ModelCreatesActionLogsTrait;
    use ModelBelongsToVehicleTrait;
    use ModelBelongsToTypeTrait;
    use ModelHasDriversTrait;
    use ModelCreatesActionLogsTrait;
    use ModelHasPayableOptionsTrait;
    use SoftDeletes;
    use Notifiable;
    use ModelHasTagsTrait;
    use ModelHasModificationNumberTrait;
    use ModelHasPrefixObserverTrait;
    use ReservationsTrait;
    use ModelHasPrintingsTrait;
    use ModelIsSortableTrait;

    public $fillable = ['id'];

    protected $casts = [
        'discount_percentage'  => 'float',
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
        'valid_date'           => 'date',
        'vat_included'         => 'boolean'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_BOOKING = 'booking';
    const STATUS_CANCELLED = 'cancelled';

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_BOOKING,
            self::STATUS_ACTIVE,
            self::STATUS_CANCELLED,
        ];
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new QuoteFilter($request))->filter($builder);
    }

    public function getStationField()
    {
        return Station::QUOTE_NUMBER;
    }

    public function getPrefix()
    {
        return config('preferences.quote_prefix');
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
        if (isset($this->vehicle_id)) {
            return 'cars/car-'.$this->vehicle_id.'/quote/';
        } else {
            return 'quotes/';
        }
    }

    public function getInitialFileName()
    {
        return 'quote-'.$this->id;
    }

    public function printingView(): string
    {
        return 'quote-agreement';
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
    public function agent() {
        return $this->belongsTo(Agent::class);
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
    public function customer() {
        return $this->belongsTo(Driver::class, 'customer_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkout_station() {
        return $this->belongsTo(Station::class, 'checkout_station_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkin_station() {
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
    public function booking() {
        return $this->hasOne(Booking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source() {
        return $this->belongsTo(BookingSource::class, 'source_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function charge_type() {
        return $this->belongsTo(Type::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program() {
        return $this->belongsTo(Program::class);
    }
}
