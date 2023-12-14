<?php

namespace App\Traits;

use App\Models\Balance;
use App\Models\Payment;
use App\Models\Transaction;
use DB;

trait ModelHasBalancesTrait {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function balances() {
        return $this->morphMany(Balance::class, 'client');
    }

    public function payments() {
        return $this->morphMany(Payment::class, 'payer');
    }

    public function transactions() {
        return $this->morphMany(Transaction::class, 'payer');
    }

    public function getBalanceAttribute() {
        $debit = $this->balances()->select(DB::raw('SUM(debit) as debit'))->first()->debit;
        $credit = $this->balances()->select(DB::raw('SUM(credit) as credit'))->first()->credit;
        $balance = $debit - $credit;
        return $balance;
    }
}
