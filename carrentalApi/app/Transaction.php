<?php

namespace App;

use App\Http\Controllers\InvoicesController;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $transaction_datetime
 * @property float $debit
 * @property int|null $rental_id
 * @property int|null $booking_id
 * @property string $transactor_type
 * @property int $transactor_id
 * @property int|null $invoice_id
 * @property int $balance_id
 */
class Transaction extends Model
{
    protected $fillable = ['rental_id', 'booking_id', 'transactor_id', 'transactor_type'];

    public function transactor() {
        return $this->morphTo();
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function rental() {
        return $this->belongsTo(Rental::class);
    }

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function getVoucherAttribute() {
        return $this->invoice ? 'INV-'.$this->invoice->id : ($this->rental ? 'RNT-'.$this->rental->id : 'RES-'.$this->booking->id );
    }

    /**
     * @param \App\Rental $rental
     * @return void
     */
    public function createInvoice(Rental $rental) {
        if (!$this->invoice_id && $this->debit > 0) {
            $total = $rental->discount > 0 ? $this->debit/(100-$rental->discount)*100 : $this->debit;
            $data = [
                'invoicee_id' => $this->transactor_id,
                'invoicee_type' => $this->transactor_type,
                'invoice_type' => $this->transactor_type == Driver::class ? Invoice::RECEIPT : Invoice::INVOICE,
                'date' => Carbon::now()->format('Y-m-d'),
                'number' => '',
                'discount' => $rental->discount,
                'sub_discount_total' => $this->debit/1.24, 2,
                'fpa_perc' => 24,
                'final_fpa' => $this->debit - round($this->debit/1.24, 2),
                'total' => $total,
                'final_total' => $this->debit,
                'notes' => '',
                'brand_id' => $rental->brand_id,
                'station_id' => $rental->checkout_station_id,
                'rental_id' => $rental->id
            ];
            $rental_payments = $rental->payments()->where('payer_type', $this->transactor_type)->get();
            $invoice = (new InvoicesController)->create_invoice($data);
            $invoice->addPayments($rental_payments);
            $payer = '';
            switch($this->transactor_type) {
                case Driver::class:
                    $payer = 'driver';
                    break;
                case Agent::class:
                    $payer = 'agent';
                    break;
                case Company::class:
                    $payer = 'company';
                    break;
            }
            $options = $rental->options()->where('quantity', '>', 0)->where('gross', '>', 0)->where('payer', $payer)->get();
            foreach ($options as $option) {
                $item = new InvoiceItem();
                $item->invoice_id = $invoice->id;
                $item->code = $option->option->code;
                $item->title = ($option->option->getProfileByLanguageId('en')->title ?? '' ).' / '.$option->option->profile_title;
                $item->quantity = $option->quantity;
                $item->daily_price = $option->rate;
                $item->price = $option->gross;
                $item->save();
            }

            $invoice->load('payments');
            $invoice->load('collections');
            $invoice->load('items');
            $invoice->addPrinting();
            $invoice->sendToAade();
            $this->invoice_id = $invoice->id;
            $this->save();
        }
    }

    public static function searchTransactor($term = null): Builder {
        $transactions = self::whereNotNull('transactor_id');
        if ($term) {
            $transactions->where(function ($q) use ($term) {
                $q->whereHasMorph('transactor', [Driver::class], function($driver_q) use ($term) {
                    $driver_q->WhereHas('contact', function ($contact_q) use ($term) {
                        $contact_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%");
                    });
                })->orWhereHasMorph('transactor', [Agent::class, Company::class], function($company_q) use ($term) {
                    $company_q->where('name', 'like', '%'.$term.'%');
                });
            });
        }

        return $transactions;
    }
}
