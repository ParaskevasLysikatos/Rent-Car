<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyPreferencesResource;
use App\Models\CompanyPreferences;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CompanyPreferencesController extends Controller
{
    public function create_view(Request $data, $lng)
    {
        $preferences = CompanyPreferences::first();
        if ($preferences) {
            return view('company_preferences.create', [
                'lang'           => Language::all(),
                'preferences' => $preferences,
                'lng'            => $lng
            ]);
        }
        return view('company_preferences.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function edit(Request $request)
    {
        $company = CompanyPreferences::first();
        return new CompanyPreferencesResource($company);
    }

    public function update_api(Request $data){
        $validator = Validator::make($data->all(), [
            'id'     => 'nullable|numeric|exists:company_preferences',
            // 'name'   => 'required',
            // 'afm'    => 'required',
            'doy'    => 'nullable',
            'country' => 'nullable',
            'city'   => 'nullable',
            'job'    => 'nullable',
            'phone'  => 'nullable',
            'email'  => 'nullable',
            'website' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $preferences = CompanyPreferences::firstOrNew(['id' => $data['id']]);
        $preferences->name = $data['name'];
        $preferences->job = $data['job'];
        $preferences->afm = $data['afm'];
        $preferences->doi = $data['doi'];
        $preferences->title = $data['title'];
        $preferences->phone = $data['phone'];
        $preferences->email = $data['email'];
        $preferences->website = $data['website'];
        $preferences->mite_number = $data['mite_number'];

        if (is_array($data['station_id'])) {
            $preferences->station_id = (int)$data['station_id.id'];
        } else {
            $preferences->station_id = $data['station_id'];
        }

        $preferences->place_id = $data['place.id'];

        if (is_array($data['quote_source_id'])) {
            $preferences->quote_source_id = (int)$data['quote_source_id.id'];
        } else {
            $preferences->quote_source_id = $data['quote_source_id'];
        }


        if (is_array($data['booking_source_id'])) {
            $preferences->booking_source_id = (int)$data['booking_source_id.id'];
        } else {
            $preferences->booking_source_id = $data['booking_source_id'];
        }


        if (is_array($data['rental_source_id'])) {
            $preferences->rental_source_id = (int)$data['rental_source_id.id'];
        } else {
            $preferences->rental_source_id = $data['rental_source_id'];
        }

        $preferences->checkin_free_minutes = $data['checkin_free_minutes'];
        $preferences->vat = $data['vat'];
        $preferences->timezone = 'Europe/Athens';
        $preferences->quote_prefix = $data['quote_prefix'];
        $preferences->booking_prefix = $data['booking_prefix'];
        $preferences->rental_prefix = $data['rental_prefix'];
        $preferences->invoice_prefix = $data['invoice_prefix'];
        $preferences->receipt_prefix = $data['receipt_prefix'];
        $preferences->payment_prefix = $data['payment_prefix'];
        $preferences->pre_auth_prefix = $data['pre_auth_prefix'];
        $preferences->refund_prefix = $data['refund_prefix'];
        $preferences->refund_pre_auth_prefix = $data['refund_pre_auth_prefix'];
        $preferences->quote_available_days = $data['quote_available_days'];
        $preferences->show_rental_charges = $data['show_rental_charges'] ? true : false;
        $preferences->rental_rate_terms = $data['rental_rate_terms'];
        $preferences->rental_vehicle_condition = $data['rental_vehicle_condition'];
        $preferences->rental_gdpr = $data['rental_gdpr'];
        $preferences->invoice_first_box = $data['invoice_first_box'];
        $preferences->invoice_second_box = $data['invoice_second_box'];
        $preferences->save();

        return new CompanyPreferencesResource($preferences);

    }

    public function create(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'     => 'nullable|numeric|exists:company_preferences',
            // 'name'   => 'required',
            // 'afm'    => 'required',
            'doy'    => 'nullable',
            'country'=> 'nullable',
            'city'   => 'nullable',
            'job'    => 'nullable',
            'phone'  => 'nullable',
            'email'  => 'nullable',
            'website'=> 'nullable',
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $preferences = CompanyPreferences::firstOrNew(['id' => $data['id']]);
        $preferences->name = $data['name'];
        $preferences->job = $data['job'];
        $preferences->afm = $data['afm'];
        $preferences->doi = $data['doi'];
        $preferences->title = $data['title'];
        $preferences->phone = $data['phone'];
        $preferences->email = $data['email'];
        $preferences->website = $data['website'];
        $preferences->mite_number = $data['mite_number'];
        $preferences->station_id = $data['station_id'];
        $preferences->place_id = $data['place_id'];
        $preferences->quote_source_id = $data['quote_source_id'];
        $preferences->booking_source_id = $data['booking_source_id'];
        $preferences->rental_source_id = $data['rental_source_id'];
        $preferences->checkin_free_minutes = $data['checkin_free_minutes'];
        $preferences->vat = $data['vat'];
        $preferences->timezone = 'Europe/Athens';
        $preferences->quote_prefix = $data['quote_prefix'];
        $preferences->booking_prefix = $data['booking_prefix'];
        $preferences->rental_prefix = $data['rental_prefix'];
        $preferences->invoice_prefix = $data['invoice_prefix'];
        $preferences->receipt_prefix = $data['receipt_prefix'];
        $preferences->payment_prefix = $data['payment_prefix'];
        $preferences->pre_auth_prefix = $data['pre_auth_prefix'];
        $preferences->refund_prefix = $data['refund_prefix'];
        $preferences->refund_pre_auth_prefix = $data['refund_pre_auth_prefix'];
        $preferences->quote_available_days = $data['quote_available_days'];
        $preferences->show_rental_charges = $data['show_rental_charges'] ? true : false;
        $preferences->rental_rate_terms = $data['rental_rate_terms'];
        $preferences->rental_vehicle_condition = $data['rental_vehicle_condition'];
        $preferences->rental_gdpr = $data['rental_gdpr'];
        $preferences->invoice_first_box = $data['invoice_first_box'];
        $preferences->invoice_second_box = $data['invoice_second_box'];
        $preferences->save();


        if ($data->ajax()) {
            return response()->json($preferences);
        }

        return redirect()->back();
    }
}
