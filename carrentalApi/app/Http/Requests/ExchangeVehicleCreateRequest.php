<?php

namespace App\Http\Requests;

use App\VehicleExchange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ExchangeVehicleCreateRequest extends FormRequest
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
            'id'                    => 'nullable|numeric',
            'datetime'              => 'required_if:type,'.VehicleExchange::TYPE_OFFICE,
            'place_id'              => 'nullable|exists:places,id',
            'place_text'            => 'required',

            'departure_datetime'    => 'required_with:new_co_km,new_co_fuel_level',
            // 'departure_datetime'    => 'required_unless:vehicle_id,null',
            'new_vehicle_id'        => 'required_if:type,'.VehicleExchange::TYPE_OFFICE.'|required_with:departure_datetime,new_co_km,new_co_fuel_level,new_ci_km,new_ci_fuel_level|exists:vehicles,id',
            'new_co_km'             => 'required_with:departure_datetime',
            'new_co_fuel_level'     => 'required_with:departure_datetime',
            'driver_id'             => 'required_with:departure_datetime|exists:drivers,id',

            'replaced_vehicle_id'   => 'required|exists:vehicles,id',
            'replaced_km'           => 'nullable',
            'replaced_fuel_level'   => 'nullable',
            'replaced_station'      => 'nullable',
            'replaced_place_id'     => 'nullable',
            'replaced_place_text'   => 'nullable',
            'km'                    => 'nullable',
            'fuel_level'            => 'nullable',
        ];
    }

    /**
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator(Validator $validator)
    {
        // $id = $this->rental_id;
        // $validator->after(
        //     function ($validator) use ($id) {
        //         if (is_null(\DB::table('rentals')->where('email',$email)->first())) {
        //             $validator->errors()->add(
        //             'email',
        //             'This email does not exist on our pre-approved email list'
        //             );
        //         }
        //     }
        // );
    }
}
