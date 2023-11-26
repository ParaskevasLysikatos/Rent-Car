<?php

namespace App\Http\Requests;

use App\VehicleReservations;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RentalCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'duration'              => 'required',
            'rate'                  => 'required',
            'checkout_datetime'     => 'required|date',
            'checkout_station_id'   => 'required|exists:stations,id',
            'checkout_driver_id'    => 'required|exists:drivers,id',
            'checkout_km'           => 'required',
            'checkout_fuel_level'   => 'required',
            'checkin_datetime'      => 'nullable|date',
            'checkin_station_id'    => 'nullable|exists:stations,id',
            'checkin_user_id'       => 'nullable|exists:drivers,id',
            'checkin_km'            => 'nullable',
            'checkin_fuel_level'    => 'nullable',
            'driver_id'             => 'required',
            'vehicle_id'            => 'required',
            'booking_id'            => 'nullable|unique:rentals'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $from       = Carbon::parse($this->checkout_datetime);
            $to         = Carbon::parse($this->checkin_datetime);
            $rental_id  = null;
            $type       = null;
            if ($this->id) {
                $rental_id = $this->id;
                $type = 'rental';
            }
            $exclude_vehicle_ids = VehicleReservations::getReservedVehicles($from, $to, $rental_id, $type);
            if (in_array($this->vehicle_id, $exclude_vehicle_ids)) {
                $validator->errors()->add('vehicle_id', __('Το όχημα που επιλέξατε δεν είναι διαθέσιμο'));
            }
        });
    }
}
