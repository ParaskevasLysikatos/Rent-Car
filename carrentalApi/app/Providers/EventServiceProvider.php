<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\Transaction;
use App\Observers\VisitObserver;
use App\Observers\RentalObserver;
use App\Observers\QuoteObserver;
use App\Observers\VehicleObserver;
use App\Observers\BookingObserver;
use App\Observers\InvoiceObserver;
use App\Observers\PaymentObserver;
use App\Observers\StationObserver;
use App\Observers\TransitionObserver;
use App\Observers\BookingItemObserver;
use App\Observers\MaintenanceObserver;
use App\Observers\TransactionObserver;
use App\Observers\PricelistRangeObserver;
use App\Models\Payment;
use App\Models\PricelistRange;
use App\Models\Quote;
use App\Models\Rental;
use App\Models\ServiceVisit;

use App\Models\Transition;
use App\Models\Vehicle;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
 * The model observers for your application.
 *
 * @var array
 */
protected $observers = [
   // User::class => [UserObserver::class],

        Transaction::class =>[TransactionObserver::class],
         Payment:: class =>[PaymentObserver::class],

         Booking:: class =>[BookingObserver::class],
         Rental::class =>[RentalObserver::class],
         Quote::class =>[QuoteObserver::class],
         Vehicle::class =>[VehicleObserver::class],
         Transition::class =>[TransitionObserver::class],
         Maintenance::class =>[MaintenanceObserver::class],
         ServiceVisit::class =>[VisitObserver::class],

         PricelistRange::class =>[PricelistRangeObserver::class],
         Invoice::class =>[InvoiceObserver::class],
         BookingItem::class =>[BookingItemObserver::class]
        //
];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }


}
