<?php

namespace App\Observers;

use App\Balance;
use App\Transaction;
use Carbon\Carbon;

class TransactionObserver
{
    public function creating(Transaction $transaction) {
        $balance = new Balance;
        $balance->balance_datetime = $transaction->transaction_datetime ?? Carbon::now();
        $balance->client_id = $transaction->transactor_id;
        $balance->client_type = $transaction->transactor_type;
        $balance->debit = $transaction->debit;
        $balance->save();

        $transaction->balance_id = $balance->id;
    }

    public function updated(Transaction $transaction) {
        $balance = Balance::find($transaction->balance_id);
        $balance->debit = $transaction->debit;
        $balance->save();
    }

    public function deleted(Transaction $transaction) {
        $balance = Balance::find($transaction->balance_id);
        $balance->delete();
    }
}
