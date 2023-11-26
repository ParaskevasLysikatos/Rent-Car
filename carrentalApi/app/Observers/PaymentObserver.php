<?php

namespace App\Observers;

use App\Balance;
use App\Payment;
use Carbon\Carbon;

class PaymentObserver
{
    public function saving(Payment $payment)
    {
        if ($payment->payment_type == Payment::REFUND_TYPE || $payment->payment_type == Payment::REFUND_PRE_AUTH_TYPE) {
            if ($payment->amount >= 0) {
                throw new InvalidPaymentSignException('Refund can\'t have positive or 0 amount');
            }
        } else if ($payment->amount <= 0) {
            throw new InvalidPaymentSignException('Payment can\'t have negative or 0 amount');
        }
    }

    public function saved(Payment $payment)
    {
        $booking = $payment->booking();
        $rental = $payment->rental();

        if ($booking) {
            $booking->total_paid = $booking->getTotalPaid();

            $booking->save();
        }

        if ($rental) {
            $rental->total_paid = $rental->getTotalPaid();

            $rental->save();
        }
    }

    public function creating(Payment $payment) {
        $balance = new Balance;
        $balance->balance_datetime = $payment->payment_datetime ?? Carbon::now();
        $balance->client_id = $payment->payer_id;
        $balance->client_type = $payment->payer_type;
        $balance->credit = $payment->amount;
        $balance->save();

        $payment->balance_id = $balance->id;
    }

    public function updated(Payment $payment) {
        $balance = Balance::find($payment->balance_id);
        $balance->credit = $payment->amount;
        $balance->save();
    }

    public function deleted(Payment $payment) {
        $booking = $payment->booking();

        if ($booking) {
            $booking->total_paid = $booking->getTotalPaid();

            $booking->save();
        }

        $rental = $payment->rental();
        if ($rental) {
            $rental->total_paid = $rental->getTotalPaid();

            $rental->save();
        }

        $balance = Balance::find($payment->balance_id);
        $balance->delete();
    }
}
