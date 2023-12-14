<?php

namespace App\Observers;

use App\Models\Vehicle;
use App\Rental;
use App\Booking;
use App\Transition;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VehicleObserver
{
    public function retrieved(Vehicle $vehicle) //when calculate status of vehicles depending on vehicle_statuses table
    {
        $today = Carbon::now(); // gets also time
        $rental = null;
        $booking = null;
        $transition = null;
        $from = '';
        $to = '';
        $statusCheck = DB::table('vehicle_statuses')->where('vehicle_id', $vehicle->id)->latest()->first();
        if ($statusCheck) { // status check exists

            if ($statusCheck->status == 'rental') { // is rental
                $rental = DB::table('rentals')->where('id', $statusCheck->rental_id)->where('deleted_at', NULL)->first();
                if ($rental) { // exists
                    $from = $rental->checkout_datetime;
                    $to = $rental->checkin_datetime;
                    if ($from <= $today && $today <= $to) { // is in range of this status
                        $vehicle->status = 'rental';
                        $vehicle->save();
                    } else { // is not
                        $vehicle->status = 'available';
                        $vehicle->save();
                    }
                } else { //was deleted
                    $vehicle->status = 'available';
                    $vehicle->save();
                }
            } else if ($statusCheck->status == 'booking') { // is booking
                $booking = DB::table('bookings')->where('id', $statusCheck->booking_id)->where('deleted_at', NULL)->first();
                if ($booking) { // exists
                    $from = $booking->checkout_datetime;
                    $to = $booking->checkin_datetime;
                    if ($from <= $today && $today <= $to) { // is in range of this status
                        $vehicle->status = 'booking';
                        $vehicle->save();
                    } else { // is not
                        $vehicle->status = 'available';
                        $vehicle->save();
                    }
                } else { //was deleted
                    $vehicle->status = 'available';
                    $vehicle->save();
                }
            } else if ($statusCheck->status == 'transition') { // is booking
                $transition = DB::table('transitions')->where('id', $statusCheck->transition_id)->where('deleted_at', NULL)->first();
                if ($transition) { // exists
                    $from = $transition->co_datetime;
                    $to = $transition->ci_datetime;
                    if ($from <= $today && $today <= $to) { // is in range of this status
                        $vehicle->status = 'transition';
                        $vehicle->save();
                    } else { // is not
                        $vehicle->status = 'available';
                        $vehicle->save();
                    }
                } else { //was deleted
                    $vehicle->status = 'available';
                    $vehicle->save();
                }
            }
        } else { //has not even started to have a status yet
            $vehicle->status = 'available';
            $vehicle->save();
        }
    }
}
