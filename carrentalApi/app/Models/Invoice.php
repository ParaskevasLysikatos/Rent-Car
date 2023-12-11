<?php

namespace App;

use App\Filters\InvoiceFilter;
use App\Http\Controllers\MydataController;
use Illuminate\Database\Eloquent\Model;
use App\InvoiceItem;
use App\Traits\ModelHasPaymentsTrait;
use App\Traits\ModelHasPrefixObserverTrait;
use App\Traits\ModelHasPrintingsTrait;
use App\Traits\ModelIsSortableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $id
 * @property int $client_id
 * @property int|null $company_id
 * @property string $type
 * @property string|null $range
 * @property int $number
 * @property string $date
 * @property float $fpa
 * @property float|null $discount
 * @property string|null $datePayed
 * @property string|null $notes
 * @property string $payment_way
 * @property string $invoicee_type
 * @property int $invoicee_id
 * @property float|null $sub_discount_total
 * @property float|null $fpa_perc
 * @property float|null $final_fpa
 * @property float|null $final_total
 * @property int $brand_id
 */
class Invoice extends Model
{
    use SoftDeletes;
    use ModelHasPrefixObserverTrait;
    use ModelHasPaymentsTrait;
    use ModelHasPrintingsTrait;
    use ModelIsSortableTrait;

    protected $fillable = ['id'];

    const INVOICE = 'invoice';
    const RECEIPT = 'receipt';
    const TYPES = [
        self::INVOICE,
        self::RECEIPT
    ];

    public function scopeFilter(Builder $builder, $request)
    {
        return (new InvoiceFilter($request))->filter($builder);
    }

    public function getDocumentsPath()
    {
        return 'cars/car-'.$this->vehicle_id.'/invoice/';
    }

    public function getInitialFileName()
    {
        $view = $this->printingView();
        return $view.'-'.$this->id;;
    }

    public function printingView(): string
    {
        $view = 'receipt';
        if ($this->type == Invoice::INVOICE) {
            $view = 'invoice';
        }

        return $view;
    }

    public function getStationField()
    {
        $number = Station::RECEIPT_NUMBER;
        if ($this->type == self::INVOICE) {
            $number = Station::INVOICE_NUMBER;
            if (($this->invoicee_type == Company::class && $this->invoicee->foreign_afm)
                || ($this->invoicee_type == Agent::class && $this->invoicee->company->foreign_afm)) {
                $number = Station::FOREIGN_NUMBER;
            }
        }
        return $number;
    }

    public function getPrefix()
    {
        $prefix = config('preferences.receipt_prefix');
        if ($this->type == self::INVOICE) {
            $prefix = config('preferences.invoice_prefix');
            if (($this->invoicee_type == Company::class && $this->invoicee->foreign_afm)
                || ($this->invoicee_type == Agent::class && $this->invoicee->company->foreign_afm)) {
                $prefix .= '-FOR';
            }
        }
        return $prefix;
    }

    public static function getPrefixField()
    {
        return 'station_id';
    }

    public function getPrefixName()
    {
        return $this->station->code;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver2()
    {
        return $this->belongsTo(Driver::class, 'invoicee_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'client_id', 'id');
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
    public function company2()
    {
        return $this->belongsTo(Company::class, 'invoicee_id', 'id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'invoicee_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function rentals() {
        return $this->hasManyThrough(Rental::class, Transaction::class, 'invoice_id', 'id', 'id', 'rental_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station() {
        return $this->belongsTo(Station::class);
    }

    /**
     * @return \App\Rental
     */
    public function rental() {
        return $this->belongsTo(Rental::class);
    }

    public function addItem($code, $title, $daily_price, $price, $quantity){
        $item = new InvoiceItem();
        $item->invoice_id = $this->id;
        $item->code = $code;
        $item->title = $title;
        $item->price = $price;
        $item->quantity = $quantity;
        $item->daily_price = $daily_price;
        $item->save();
        return $item;
    }

    public function invoicee() {
        return $this->morphTo();
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function instance()
    {
        return $this->hasOne(InvoiceInstance::class);
    }

    public function getNetTotalAttribute()
    {
        // $total = round($this->items()->get()->pluck('price')->sum()/1.24, 2);
        $total = $this->final_total - $this->final_fpa;
        return $total;
    }

    public function sendToAade()
    {
        $this->load('payments');
        $this->load('collections');
        $this->load('instance');
        $seq = explode('-', $this->sequence_number);
        $data = [
            'aa' => end($seq),
            'invoice' => $this->sequence_number,
            'branch' => $this->station->aade_branch,
            'type' => $this->type,
            'series' => $this->station->code,
            'issueDate' => $this->date,
            'netValue' => $this->net_total,
            'vatAmount' => $this->final_fpa
        ];

        if (($this->invoicee_type == Company::class && $this->invoicee->foreign_afm)
            || ($this->invoicee_type == Agent::class && $this->invoicee->company->foreign_afm)) {
            $data['series'] = 'FOR-'.$data['series'];
            $data['type'] = self::RECEIPT;
        }
        else if ($data['type'] == 'invoice') {
            $data['invoicee'] = [
                'vatNumber' => $this->instance->afm,
                'postalCode' => $this->instance->zip_code,
                'city' => $this->instance->city
            ];

        }
        $payments = [];
        foreach ($this->collections as $payment) {
            $payments[] = [
                'type' => $payment->aade_type,
                'amount' => $payment->amount
            ];
        }
        $data['payments'] = $payments;
        $sent = MydataController::send($data);
        if ($sent) {
            $this->sent_to_aade = true;
            $this->save();
        }
    }
}