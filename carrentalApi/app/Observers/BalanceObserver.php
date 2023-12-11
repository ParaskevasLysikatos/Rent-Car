<?php

namespace App\Observers;

use App\Balance;

class BalanceObserver
{
    public function saved(Balance $balance) {
        $client = $balance->client;
        $client->balance = $client->balance + $balance->debit - $balance->credit;
        $client->save();
    }
}
