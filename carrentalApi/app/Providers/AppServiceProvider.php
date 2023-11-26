<?php

namespace App\Providers;

use Blade;
use Config;
use Schema;
use App\Rental;
use App\Vehicle;
use App\Booking;
use App\Invoice;
use App\Payment;
use App\Transition;
use App\BookingItem;
use App\Maintenance;
use App\Transaction;
use App\ServiceVisit;
use App\PricelistRange;
use App\CompanyPreferences;
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
use Illuminate\Support\ServiceProvider;
use App\Observers\PricelistRangeObserver;
use App\Quote;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('company_preferences')) {
            $preference = CompanyPreferences::first();
            if ($preference) {
                Config::set('preferences.station_id', $preference->station_id);
                Config::set('preferences.place_id', $preference->place_id);
                Config::set('preferences.quote_source_id', $preference->quote_source_id);
                Config::set('preferences.booking_source_id', $preference->booking_source_id);
                Config::set('preferences.rental_source_id', $preference->rental_source_id);
                Config::set('preferences.checkin_free_minutes', $preference->checkin_free_minutes);
                Config::set('preferences.vat', $preference->vat);
                Config::set('preferences.timezone', $preference->timezone);
                Config::set('preferences.quote_prefix', $preference->quote_prefix);
                Config::set('preferences.booking_prefix', $preference->booking_prefix);
                Config::set('preferences.rental_prefix', $preference->rental_prefix);
                Config::set('preferences.invoice_prefix', $preference->invoice_prefix);
                Config::set('preferences.receipt_prefix', $preference->receipt_prefix);
                Config::set('preferences.payment_prefix', $preference->payment_prefix);
                Config::set('preferences.pre_auth_prefix', $preference->pre_auth_prefix);
                Config::set('preferences.refund_prefix', $preference->refund_prefix);
                Config::set('preferences.refund_pre_auth_prefix', $preference->refund_pre_auth_prefix);
                Config::set('preferences.quote_available_days', $preference->quote_available_days);
                Config::set('preferences.show_rental_charges', $preference->show_rental_charges);
            }
        }

        // VehicleStatus::observe(VehicleStatusObserver::class);

        // Balance::observe(BalanceObserver::class);
        Transaction::observe(TransactionObserver::class);
        Payment::observe(PaymentObserver::class);

        Booking::observe(BookingObserver::class);
        Rental::observe(RentalObserver::class);
        Quote::observe(QuoteObserver::class);
        Vehicle::observe(VehicleObserver::class);
        Transition::observe(TransitionObserver::class);
        Maintenance::observe(MaintenanceObserver::class);
        ServiceVisit::observe(VisitObserver::class);

        PricelistRange::observe(PricelistRangeObserver::class);
        // Station::observe(StationObserver::class);
        // CompanyPreferences::observe(CompanyPreferencesObserver::class);
        Invoice::observe(InvoiceObserver::class);
        BookingItem::observe(BookingItemObserver::class);

        Blade::component('components.affects-vehicle-popup', 'affectsVehiclePopup');
        Blade::component('components.higher-km-confirmation', 'higherKmConfirmation');
        Blade::component('components.image', 'image');
        Blade::component('components.document', 'document');
        Blade::component('components.template-modal', 'templateModal');
        Blade::component('components.modal', 'modal');
        Blade::component('components.btn-modal', 'btnModal');
        Blade::component('components.btn-add-modal', 'btnAddModal');
        Blade::component('components.selector', 'selector');
        Blade::component('components.places-selector', 'placesSelector');
        Blade::component('components.driver-selector', 'driverSelector');
        Blade::component('components.source-selector', 'sourceSelector');
        Blade::component('components.brand-selector', 'brandSelector');
        Blade::component('components.agent-selector', 'agentSelector');
        Blade::component('components.station-selector', 'stationSelector');
        Blade::component('components.user-selector', 'userSelector');
        Blade::component('components.company-selector', 'companySelector');
        Blade::component('components.vehicle-selector', 'vehicleSelector');
        Blade::component('components.option-selector', 'optionSelector');
        Blade::component('components.group-selector', 'groupSelector');
        Blade::component('components.contact-selector', 'contactSelector');
        Blade::component('components.swap-input', 'swapInput');
        Blade::component('components.duration', 'duration');
        Blade::component('components.documents', 'documents');
        Blade::component('components.transactor-selector', 'transactorSelector');
        Blade::component('components.sub-account-selector', 'subaccountSelector');
        Blade::component('components.typing-selector', 'typingSelector');
        Blade::component('components.datetimepicker', 'datetimepicker');
        Blade::component('components.tags', 'tags');
    }
}
