<?php

namespace App\Observers;

use App\BookingItem;

class BookingItemObserver
{
    public function saving(BookingItem $bookingItem) {
        $bookingItem->net = round($bookingItem->gross/1.24, 2);
    }
}
