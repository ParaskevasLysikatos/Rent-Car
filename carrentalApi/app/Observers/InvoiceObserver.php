<?php

namespace App\Observers;

use App\Agent;
use App\Invoice;
use App\InvoiceInstance;

class InvoiceObserver
{
    public function created(Invoice $invoice) {

        $invoice_instance = new InvoiceInstance();
        $invoicee = $invoice->invoicee;

        $invoice_instance->invoice_id = $invoice->id;
        $invoice_instance->rental_sequence_number = $invoice->rental->sequence_number;
        $invoice_instance->booking_sequence_number = $invoice->rental->booking->sequence_number ?? '';
        $invoice_instance->voucher_no = $invoice->rental->agent_voucher;
        $invoice_instance->checkout_datetime = $invoice->rental->checkout_datetime;
        $invoice_instance->checkin_datetime = $invoice->rental->checkin_datetime;
        $invoice_instance->days = $invoice->rental->duration;
        $invoice_instance->checkout_station = $invoice->rental->checkout_station->profile_title;
        $invoice_instance->checkout_station_phone = $invoice->rental->checkout_station->phone;
        $invoice_instance->licence_plate = $invoice->rental->vehicle->licence_plate;
        $invoice_instance->group = $invoice->rental->type->category->profile_title.'-'.$invoice->rental->type->international_code;
        $invoice_instance->vehicle_whole_model = $invoice->rental->vehicle->whole_model;
        $invoice_instance->checkout_km = $invoice->rental->checkout_km;
        $invoice_instance->checkin_km = $invoice->rental->checkin_km;
        $invoice_instance->driven_km = $invoice->rental->checkin_km - $invoice->rental->checkout_km;
        $invoice_instance->checkout_fuel_level = $invoice->rental->checkout_fuel_level;
        $invoice_instance->checkin_fuel_level = $invoice->rental->checkin_fuel_level;
        // $invoice_instance->rental_agent = $invoice->rental->rental_agent;

        if ($invoice->type == Invoice::INVOICE) {
            $invoice_instance->driver = $invoice->rental->driver->full_name;
            $invoice_instance->licence_number = $invoice->rental->driver->licence_number;
            $invoice_instance->licence_created = $invoice->rental->driver->licence_created;
            $invoice_instance->licence_expire = $invoice->rental->driver->licence_expire;
            $invoice_instance->licence_country = $invoice->rental->driver->licence_country;
            $invoice_instance->identification_number = $invoice->rental->driver->identification_number;
            $invoice_instance->identification_created = $invoice->rental->driver->identification_created;
            $invoice_instance->identification_expire = $invoice->rental->driver->identification_expire;
            $invoice_instance->identification_country = $invoice->rental->driver->identification_country;

            if (get_class($invoicee) == Agent::class) {
                $invoicee = $invoicee->company;
            }

            $invoice_instance->name = $invoicee->name;
            $invoice_instance->occupation = $invoicee->job;
            $invoice_instance->afm = $invoicee->afm;
            $invoice_instance->doy = $invoicee->doy;
            $invoice_instance->address = $invoicee->address;
            $invoice_instance->city = $invoicee->city;
            $invoice_instance->zip_code = $invoicee->zip_code;
            $invoice_instance->country = $invoicee->country;
            $invoice_instance->email = $invoicee->email;
            $invoice_instance->phone = $invoicee->phone;
        } else {
            $invoice_instance->name = $invoicee->full_name;
            $invoice_instance->address = $invoicee->address;
            $invoice_instance->city = $invoicee->city;
            $invoice_instance->zip_code = $invoicee->zip;
            $invoice_instance->country = $invoicee->country;
            $invoice_instance->email = $invoicee->email;
            $invoice_instance->phone = $invoicee->phone;
            $invoice_instance->licence_number = $invoicee->licence_number;
            $invoice_instance->licence_created = $invoicee->licence_created;
            $invoice_instance->licence_expire = $invoicee->licence_expire;
            $invoice_instance->licence_country = $invoicee->licence_country;
            $invoice_instance->identification_number = $invoicee->identification_number;
            $invoice_instance->identification_created = $invoicee->identification_created;
            $invoice_instance->identification_expire = $invoicee->identification_expire;
            $invoice_instance->identification_country = $invoicee->identification_country;
            $invoice_instance->birth_date = $invoicee->birthday;
            $invoice_instance->birth_place = $invoicee->birth_place;
        }
        $invoice_instance->save();
    }

    public function deleted(Invoice $invoice) {
        $transactions = $invoice->transactions;
        foreach ($transactions as $transaction) {
            $transaction->invoice_id = null;
            $transaction->save();
        }
        $station_field = $invoice->getStationField();
        $current_prefix = $invoice->getPrefixName();

        $station = $invoice->station;
        $number = $station->{$station_field};

        $sequence_number = $invoice->getPrefix() . '-'. $current_prefix . '-' . $number;
        if ($sequence_number == $invoice->sequence_number) {
            $station->{$station_field}--;
            $station->save();
        }
    }
}
