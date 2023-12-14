<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    public function payment_link() {
        return $this->morphTo();
    }
}
