<?php

namespace App\Models;

use App\Filters\PaymentFilter;
use App\Traits\ModelBelongsToUserTrait;
use App\Traits\ModelCreatesActionLogsTrait;
use App\Traits\ModelHasDocumentsTrait;
use App\Traits\ModelHasPrefixObserverTrait;
use App\Traits\ModelHasPrintingsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $user_id
 * @property int|null $balance
 * @property float $amount
 * @property string|null $method
 * @property string|null $comments
 * @property string|null $reference
 * @property string $payment_datetime
 * @property int $station_id
 * @property int|null $place_id
 * @property string|null $place_text
 * @property string|null $bank_transfer_account
 * @property string|null $cheque_due_date
 * @property string|null $cheque_number
 * @property string|null $credit_card_year
 * @property string|null $credit_card_month
 * @property string|null $credit_card_number
 * @property string $payer_type
 * @property int $payer_id
 * @property int $balance_id
 */
class Payment extends Model
{
    use SoftDeletes;
    use Notifiable;
    use ModelBelongsToUserTrait;
    // use ModelHasDocumentsTrait;
    use ModelCreatesActionLogsTrait;
    use ModelHasPrefixObserverTrait;
    use ModelHasPrintingsTrait;

    protected $fillable = ['id'];
    protected $casts = [
        'amount' => 'float',
    ];

    const REFUND_TYPE = 'refund';
    const PRE_AUTH_TYPE = 'pre-auth';
    const PAYMENT_TYPE = 'payment';
    const REFUND_PRE_AUTH_TYPE = 'refund-pre-auth';

    const CASH_METHOD = 'cash';
    const CARD_METHOD = 'credit_card';
    const CHEQUE_METHOD = 'cheque';
    const BANK_METHOD = 'bank_transfer';

    const CARD_TYPES = [
        'visa' => 'VISA',
        'mastercard' => 'MASTERCARD',
        'american-express' => 'AMERICAN EXPRESS',
        'diners' => 'DINERS',
        'visa-debit' => 'VISA-debit',
        'master-debit' => 'MASTER-debit'
    ];

    const PAYMENTS_METHODS = [
        self::CASH_METHOD,
        self::CARD_METHOD,
        self::CHEQUE_METHOD,
        self::BANK_METHOD
    ];

    public function scopeFilter(Builder $builder, $request)
    {
        return (new PaymentFilter($request))->filter($builder);
    }

    public function getStationField()
    {
        $station_field = '';
        switch ($this->payment_type) {
            case (self::REFUND_TYPE):
                $station_field = Station::REFUND_NUMBER;
                break;
            case (self::PRE_AUTH_TYPE):
                $station_field = Station::PRE_AUTH_NUMBER;
                break;
            case (self::PAYMENT_TYPE):
                $station_field = Station::PAYMENT_NUMBER;
                break;
            case (self::REFUND_PRE_AUTH_TYPE):
                $station_field = Station::REFUND_PRE_AUTH_NUMBER;
                break;
        }
        return $station_field;
    }

    public function getPrefix()
    {
        $prefix = '';
        switch($this->payment_type) {
            case (self::REFUND_TYPE):
                $prefix = config('preferences.refund_prefix');
                break;
            case (self::PRE_AUTH_TYPE):
                $prefix = config('preferences.pre_auth_prefix');
                break;
            case (self::PAYMENT_TYPE):
                $prefix = config('preferences.payment_prefix');
                break;
            case (self::REFUND_PRE_AUTH_TYPE):
                $prefix = config('preferences.refund_pre_auth_prefix');
                break;
        }
        return $prefix;
    }

    public function printingView(): string
    {
        $view = 'payment';

        if ($this->payment_type == self::REFUND_TYPE || $this->payment_type == self::REFUND_PRE_AUTH_TYPE) {
            $view = 'refund';
        }
        return $view;
    }

    public static function validType($type) {
        return in_array($type, [self::REFUND_TYPE, self::PRE_AUTH_TYPE, self::PAYMENT_TYPE, self::REFUND_PRE_AUTH_TYPE]);
    }

    public static function getPrefixField()
    {
        return 'station_id';
    }

    public function getPrefixName()
    {
        return $this->station->code;
    }

    public function getTypeTitleAttribute() {
        $title = '';
        switch($this->payment_type) {
            case (self::REFUND_TYPE):
                $title = __('Επιστροφή χρημάτων');
                break;
            case (self::PRE_AUTH_TYPE):
                $title = __('Εγγύηση');
                break;
            case (self::PAYMENT_TYPE):
                $title = __('Είσπραξη');
                break;
            case (self::REFUND_PRE_AUTH_TYPE):
                $title = __('Επιστροφή Εγγύησης');
                break;
        }
        return $title;
    }

    public static function getTypeTitle($type) {
        $title = '';
        switch($type) {
            case (self::REFUND_TYPE):
                $title = __('Επιστροφή χρημάτων');
                break;
            case (self::PRE_AUTH_TYPE):
                $title = __('Εγγύηση');
                break;
            case (self::PAYMENT_TYPE):
                $title = __('Είσπραξη');
                break;
            case (self::REFUND_PRE_AUTH_TYPE):
                $title = __('Επιστροφή Εγγύησης');
                break;
        }
        return $title;
    }

    public static function getMethodTitle($method) {// v2 used
        $title = '';
        switch($method) {
            case (self::CASH_METHOD):
                $title = __('Μετρητά');
                break;
            case (self::CARD_METHOD):
                $title = __('Πιστωτική κάρτα');
                break;
            case (self::CHEQUE_METHOD):
                $title = __('Επιταγή');
                break;
            case (self::BANK_METHOD):
                $title = __('Τραπεζική κατάθεση');
                break;
        }
        return $title;
    }

    public function getMethodTitleAttribute2() { //v2 used
        $title = '';
        self::getMethodTitle($this->method);
        return $title;
    }

    public function getMethodTitleAttribute()//v1
    {
        $title = '';
        switch ($this->method) {
            case (self::CASH_METHOD):
                $title = __('Μετρητά');
                break;
            case (self::CARD_METHOD):
                $title = __('Πιστωτική κάρτα');
                break;
            case (self::CHEQUE_METHOD):
                $title = __('Επιταγή');
                break;
            case (self::BANK_METHOD):
                $title = __('Τραπεζική κατάθεση');
                break;
        }
        return $title;
    }

    public function getAadeTypeAttribute() {
        $aade_type = '';
        switch($this->method) {
            case (self::CASH_METHOD):
                $aade_type = 3; // Μετρητά
                break;
            case (self::CARD_METHOD):
                $aade_type = 3; // Μετρητά
                break;
            case (self::CHEQUE_METHOD):
                $aade_type = 4; // Επιταγή
                break;
            case (self::BANK_METHOD):
                $aade_type = 1 ; // Τραπεζική κατάθεση Ημεδαπής
                if ($this->foreigner) {
                    $aade_type = 2; // Τραπεζική κατάθεση Αλλοδαπής
                }
                break;
        }

        return $aade_type;
    }

    public function getDocumentsPath()
    {
        return 'payments/payment'.$this->id. '/';
    }

    public function getInitialFileName()
    {
        return 'payment';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station() {
        return $this->belongsTo(Station::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place() {
        return $this->belongsTo(Place::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payer() {
        return $this->morphTo();
    }

    public function rentals() {
        return $this->morphedByMany(Rental::class, 'payment_link');
    }

    public function rental() {
        return $this->rentals()->first();
    }

    public function bookings() {
        return $this->morphedByMany(Booking::class, 'payment_link');
    }

    public function booking() {
        return $this->bookings()->first();
    }

    public function invoices() {
        return $this->morphedByMany(Invoice::class, 'payment_link');
    }

    public function invoice() {
        return $this->invoices()->first();
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function links() {
        return $this->hasMany(PaymentLink::class);
    }
}
