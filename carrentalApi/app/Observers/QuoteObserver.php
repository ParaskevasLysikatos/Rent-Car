<?php

namespace App\Observers;

use App\Models\Quote;
use Carbon\Carbon;
use App;

class QuoteObserver
{
    public function retrieved()//when quotes are older than today and expired, make them cancel
    {
        $today= Carbon::today();
        $quotes=Quote::query()
        ->whereDate('valid_date','<=',$today)
        ->where('status','active')
        ->update([
            'status'=>'cancelled',
            'cancel_reason_id' => 2
        ]);
    }
}
