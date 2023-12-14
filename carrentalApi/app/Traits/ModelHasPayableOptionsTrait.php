<?php

namespace App\Traits;

use App\Models\Agent;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Company;
use App\Models\Driver;
use App\Exceptions\TransactionAmountLessThanInvoicedException;
use App\Models\Option;
use App\Models\Quote;
use App\Models\Rental;
use App\Models\Transaction;
use Request;

trait ModelHasPayableOptionsTrait
{

    public static function bootModelHasPayableOptionsTrait()
    {
        static::saved(function ($object) {
            // $object->addOptionsFromRequest(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(BookingItem::class)->join('options', 'options.id', '=', 'booking_items.option_id')
            ->orderBy('booking_items.quantity', 'desc')->orderBy('options.order')->select('booking_items.*');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function extras()
    {
        return $this->options()->whereHas('option', function ($q) {
            $q->where('option_type', 'extras');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function insurances()
    {
        return $this->options()->whereHas('option', function ($q) {
            $q->where('option_type', 'insurances');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transports()
    {
        return $this->options()->whereHas('option', function ($q) {
            $q->where('option_type', 'transport');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rental_charges()
    {
        return $this->options()->whereHas('option', function ($q) {
            $q->where('option_type', 'rental_charges');
        });
    }


    private function addTransactor(&$transactors, $transactor, $cost)
    {
        if ($cost >= 0) {
            if (!isset($transactors[$transactor])) {
                $transactors[$transactor] = 0;
            }
            $transactors[$transactor] += $cost;
        }
    }

    public function addOptionsFromRequest()
    {
        if (Request::has('options') && (!Request::has('options_added') || Request::get('options_added') != true)) {
            $customer_id = $this->customer->id ?? $this->customer_id;
            if (get_class($this) == Rental::class) {
                $arg = [
                    'id' => 'rental_id',
                    'value' => $this->id
                ];
            } else if (get_class($this) == Booking::class) {
                $arg = [
                    'id' => 'booking_id',
                    'value' => $this->id
                ];
            }

            $transactors = [];
            if (get_class($this) == Rental::class || (get_class($this) == Booking::class && !$this->rental)) {
                $existing_transactors = Transaction::where($arg['id'], $this->id)->get();
                foreach ($existing_transactors as $existing_transactor) {
                    $transactors[$existing_transactor->transactor_type . '-' . $existing_transactor->transactor_id] = 0;
                }
            }

            $this->addTransactor($transactors, Driver::class . '-' . $customer_id, 0);
            if ($this->agent_id) {
                $this->addTransactor($transactors, Agent::class . '-' . $this->agent->id, 0);
            }
            if ($this->company_id) {
                $this->addTransactor($transactors, Company::class . '-' . $this->company->id, 0);
            }
            foreach (Request::get('options') as $option) {
                if (!isset($option['id'])) {
                    $args = [
                        'option_id' => $option['option_id']
                    ];
                    $relative = get_class($this) == Quote::class ? 'quote_id' : (get_class($this) == Booking::class ? 'booking_id' : 'rental_id');
                    $args[$relative] = $this->id;
                    $booking_item = BookingItem::firstOrNew($args);
                } else {
                    $booking_item = BookingItem::find($option['id']);
                }


                if (isset($option['quantity']) && !is_null($option['cost'])) {
                    $booking_item->payer = $option['payer'];
                    $booking_item->rate = $option['cost'];
                    if (isset($option['total-cost'])) { //v1
                        $booking_item->net = round($option['total-cost'] / 1.24, 2);
                        $booking_item->gross = $option['total-cost'] ?? 0;
                    } else { //v2
                        $booking_item->net = round($option['total_cost'] / 1.24, 2);
                        $booking_item->gross = $option['total_cost'] ?? 0;
                    }
                    $booking_item->quantity = $option['quantity'];
                    $booking_item->start = $option['start'] ?? null;
                    $booking_item->end = $option['end'] ?? null;
                    $booking_item->duration = $option['duration'];
                    if (!$booking_item->exists) {
                       $option = Option::find($option['option_id']);
                       $booking_item->daily_cost = $option->active_daily_cost;
                    }
                    $booking_item->save();

                    if ($booking_item->payer == 'driver' && ($this->customer || $this->customer_id)) {
                        $this->addTransactor($transactors, Driver::class . '-' . $customer_id, $booking_item->gross);
                    } else if ($booking_item->payer == 'agent') {
                        $this->addTransactor($transactors, Agent::class . '-' . $this->agent->id, $booking_item->gross);
                    } else if ($booking_item->payer == 'company') {
                        $this->addTransactor($transactors, Company::class . '-' . $this->company->id, $booking_item->gross);
                    }
                } else if ($booking_item->exists) {
                    $booking_item->delete();
                }
            }

            if (get_class($this) == Rental::class || (get_class($this) == Booking::class && !$this->rental)) {
                foreach ($transactors as $transactor => $cost) {
                    $transactor = explode('-', $transactor);
                    $args = ['transactor_id' =>  $transactor[1], 'transactor_type' => $transactor[0]];
                    $args[$arg['id']] = $arg['value'];
                    $transactions = Transaction::where($args)->get();
                    if ($transactions->isEmpty()) {
                        $transaction = new Transaction($args);
                        if ($this->discount) {
                            $cost = $cost - $cost * ($this->discount / 100);
                        }
                        $transaction->debit = $cost;
                        $transaction->save();
                    } else {
                        $new_transaction = false;
                        $existing_transaction = null;
                        $existing_transactions = [];
                        foreach ($transactions as $transaction) {
                            if ($transaction->exists) {
                                if ($transaction->invoice_id) {
                                    $cost -= $transaction->debit;
                                    if ($cost < 0) {
                                        // Ti ginetai otan erxetai nwritera - pistotiko timologio klp
                                        // throw new TransactionAmountLessThanInvoicedException('New amount is less than invoiced');
                                    }
                                    $new_transaction = true;
                                } else {
                                    $existing_transaction = $transaction;
                                    $existing_transactions[] = $transaction;
                                }
                            }
                        }
                        if ($this->discount) {
                            $cost = $cost - $cost * ($this->discount / 100);
                        }
                        if ($existing_transaction) {
                            $existing_transaction->debit = $cost;
                            $existing_transaction->save();
                            foreach ($existing_transactions as $transaction) {
                                if ($existing_transaction->id != $transaction->id) {
                                    $transaction->delete();
                                }
                            }
                        }
                        if ($new_transaction) {
                            $new_transaction = new Transaction($args);
                            $new_transaction->debit = $cost;
                            $new_transaction->save();
                        }
                    }
                }
            }

            Request::merge(['options_added' => true]);
        }
    }
}
