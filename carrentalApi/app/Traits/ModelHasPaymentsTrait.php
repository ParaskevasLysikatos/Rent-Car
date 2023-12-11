<?php

namespace App\Traits;

use App\Agent;
use App\Booking;
use App\Company;
use App\Driver;
use App\Http\Controllers\PaymentsController;
use App\Payment;
use App\Rental;
use DB;
use Illuminate\Support\Collection;
use Request;

trait ModelHasPaymentsTrait {
    public static function bootModelHasPaymentsTrait() {
        static::saved(function ($object) {
            // $object->addPaymentsFromRequest(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function payments() {
        return $this->morphToMany(Payment::class, 'payment_link');
    }

    public function collections() {
        return $this->payments()->where('payment_type', Payment::PAYMENT_TYPE);
    }

    public function pre_auths() {
        return $this->payments()->where('payment_type', Payment::PRE_AUTH_TYPE);
    }

    public function getTotalPaid(): float
    {
        $paid = $this->payments()->whereNotIn('payment_type', [Payment::PRE_AUTH_TYPE, Payment::REFUND_PRE_AUTH_TYPE])->select(DB::raw('SUM(amount) as amount'))->groupBy('payment_link_id')->first()->amount ?? 0;

        return $paid;
    }

    public function addPayment(Payment $payment) {
        $this->payments()->attach($payment->id);
    }

    public function addPayments(Collection $payments) {
        foreach ($payments as $payment) {
            $this->addPayment($payment);
        }
    }


    public function addPaymentsFromRequest()
    {
        if (Request::has('payments') && !Request::has('paid')) {
            Request::merge(['paid' => true]);
            foreach (Request::get('payments') as $payment) {
                $data = $payment;
                $data['id'] = $data['id'] ?? null;
                $data['payer_type'] = $data['payer_id'] == 'driver' ? Driver::class : ($data['payer_id'] == 'company' ? Company::class : ($data['payer_id'] == 'agent' ? Agent::class : null));
                $data['payer_id'] = $data['payer_id'] == 'driver' ? ($this->customer->id ?? null) : ($data['payer_id'] == 'company' ? $this->company->id : ($data['payer_id'] == 'agent' ? $this->agent->id : null));
                $data['brand_id'] = $this->brand_id;
                if (get_class($this) == Rental::class) {
                    Request::merge(['rental_id' => $this->id]);
                } else if (get_class($this) == Booking::class) {
                    Request::merge(['booking_id' => $this->id]);
                }
                $payment = (new PaymentsController)->create_payment($data);
                if ($payment !== false) {
                    $this->addPayment($payment);
                    $payment->addPrinting();
                }
            }
        }
    }


    public function addPaymentsFromRequest2() {// v2
        if (Request::has('payments') && !Request::has('paid')) {
            Request::merge(['paid' => true]);
            $ids=[];
            foreach (Request::get('payments') as $payment) {
                $data = $payment;
                $data['id'] = $data['id'] ?? null;
                $data['payer_type'] = $data['payer_id'] == 'driver' ? Driver::class : ($data['payer_id'] == 'company' ? Company::class : ($data['payer_id'] == 'agent' ? Agent::class : null));
                $data['payer_id'] = $data['payer_id'] == 'driver' ? ($this->customer->id ?? null ) : ($data['payer_id'] == 'company' ? $this->company->id : ($data['payer_id'] == 'agent' ? $this->agent->id : null));
                $data['brand_id'] = $this->brand_id;
                if (get_class($this) == Rental::class) {
                    Request::merge(['rental_id' => $this->id]);
                } else if (get_class($this) == Booking::class) {
                    Request::merge(['booking_id' => $this->id]);
                }
                if(isset($data['reference'])){//comes from normal call
                $payment = (new PaymentsController)->create_payment($data);
                }else{// comes from modal
                 $data['payer_type']=$payment['payer_type'];
                $data['payer_id'] = $payment['payer_id'];
                $payment = (new PaymentsController)->update_store_api_modal($data);
                }
                if ($payment !== false) {
                   // $this->addPayment($payment);
                    $payment->addPrinting();
                }
                array_push($ids, $payment->id);
            }
            $this->payments()->sync($ids);
        }
    }



}
