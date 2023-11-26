<?php

namespace App\Http\Controllers;

use App\Language;
use App\Quote;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Cookie;
use Session;
use Validator;
use App\Driver;
use App\Http\Resources\QuoteCollection;
use App\Http\Resources\QuoteResource;
use DB;

class QuoteController extends Controller
{


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $quotes = Quote::query()->orderBy('created_at', 'desc');

        if ($term) {
            $quotes = $quotes->where('sequence_number', 'like', '%' . $term . '%')
                ->orWhere('customer_text', 'like', '%' . $term . '%')
                ->orWhere('phone', 'like', '%' . $term . '%')
                ->orWhereHas('customer.contact', function ($driver_q) use ($term) {
                    $driver_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%")
                        ->orWhere('email', $term)->orWhere('phone', $term);
                });
        }

        return new QuoteCollection($quotes->filter($data)->applyOrder($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));

    }


    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $quotes = Quote::query();

        if ($term) {
            $quotes = $quotes->where('sequence_number', 'like', '%' . $term . '%')
                ->orWhere('customer_text', 'like', '%' . $term . '%')
                ->orWhere('phone', $term)
                ->orWhereHas('customer.contact', function ($driver_q) use ($term) {
                    $driver_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%")
                        ->orWhere('email', $term)->orWhere('phone', $term);
                });
        }

        $quotes = $quotes->filter($data)->applyOrder($data)->paginate(Cookie::get('pages') ?? '5');
        return view('quotes.preview', compact(['quotes', 'lng']));
    }


    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {

            $quote = Quote::find($data['cat_id']);
            $printings = [$quote->last_printing];

            return view('quotes.create', [
                'lang'      => Language::all(),
                'quote'     => $quote,
                'lng'       => $lng,
                'duplicate' => $data->has('duplicate') && $data->duplicate == true,
                'new'       => $data->new ? $data->new : false,
                'printings' => $printings
            ]);
        }
        return view('quotes.create', ['lang' => Language::all(), 'lng' => $lng, 'duplicate' => true]);
    }

    public function create_validator($data)
    {
        $validator = Validator::make($data, [
            'customer_id'          => 'required_without_all:customer_text',
            'customer_text'        => 'required_without_all:customer_id',
            'company_id'            => 'nullable|exists:companies,id',
            'type_id'               => 'required|exists:types,id',
            'brand_id'              => 'nullable|exists:brands,id',
            'agent_id'              => 'nullable|exists:agents,id',
            'source_id'             => 'required|exists:booking_sources,id',
            'checkout_station_id'   => 'nullable|exists:stations,id',
            'checkin_station_id'    => 'nullable|exists:stations,id',
            'checkout_place_id'     => 'nullable|exists:places,id',
            'checkin_place_id'      => 'nullable|exists:places,id',
            'checkout_datetime'     => 'required|date',
            'checkin_datetime'      => 'required|date',
            'valid_date'            => 'required|date',
        ]);

        return $validator;
    }

    public function create_validatorApi($data)
    {
        $validator = Validator::make($data, [
            // 'customer_id'          => 'required_without_all:customer_text',
            'customer_driver.name'  => 'required',
            'summary_charges.charge_type_id'   => 'required',
            'company_id'            => 'nullable|exists:companies,id',
            'type_id'               => 'required|exists:types,id',
            'brand_id'              => 'nullable|exists:brands,id',
            'agent_id'              => 'nullable|exists:agents,id',
            'source_id'             => 'required|exists:booking_sources,id',
            'checkout_station_id'   => 'required|exists:stations,id',
            'checkin_station_id'    => 'required|exists:stations,id',
            'checkout_place_id'     => 'nullable|exists:places,id',
            'checkin_place_id'      => 'nullable|exists:places,id',
            'checkout_datetime'     => 'required|date',
            'checkin_datetime'      => 'required|date',
            'valid_date'            => 'required|date',
        ]);

        return $validator;
    }

    public function create_quote($data, $id = null)
    {
        $quote = Quote::firstOrNew(['id' => $id]);
        $quote->customer_id             = $data['customer_id'];
        $quote->customer_text           = $data['customer_text'];
        $quote->user_id                 = $quote->exists ? $quote->user_id : Auth::user()->id;
        $quote->company_id              = $data['company_id'];
        $quote->type_id                 = $data['type_id'];
        $quote->brand_id                = $data['brand_id'];
        $quote->agent_id                = $data['agent_id'];
        $quote->sub_account_id          = $data['sub_account_id'];
        $quote->sub_account_type        = $data['sub_account_type'];
        $quote->source_id               = $data['source_id'];
        $quote->program_id              = $data['program_id'];
        $quote->checkout_station_id     = $data['checkout_station_id'];
        $quote->checkin_station_id      = $data['checkin_station_id'];
        $quote->checkout_place_id       = $data['checkout_place_id'];
        $quote->checkout_place_text     = $data['checkout_place_text'];
        $quote->checkin_place_id        = $data['checkin_place_id'];
        $quote->checkin_place_text      = $data['checkin_place_text'];
        $quote->charge_type_id          = $data['charge_type_id'];

        $quote->confirmation_number     = $data['confirmation_number'];
        $quote->agent_voucher           = $data['agent_voucher'];

        $quote->checkout_datetime       = $data['checkout_datetime'];
        $quote->checkin_datetime        = $data['checkin_datetime'];
        $quote->duration                = $data['duration'];
        $quote->rate                    = $data['rate'];
        $quote->distance_rate           = $data['distance_rate'];
        $quote->valid_date              = $data['valid_date'];
        $quote->distance                = $data['distance'];
        $quote->discount_percentage     = $data['discount_percentage'] ?? 0;
        $quote->checkout_station_fee    = $data['checkout_station_fee'];
        $quote->checkin_station_fee     = $data['checkin_station_fee'];
        $quote->checkout_comments       = $data['checkout_notes'];
        $quote->checkin_comments        = $data['checkin_notes'];
        $quote->estimated_km            = $data['estimated_km'];
        $quote->extension_rate          = $data['extension_rate'];
        $quote->may_extend              = ($data['may_extend']) ? '1' : '0';
        $quote->comments                = $data['notes'];

        $quote->excess                  = $data['excess'];
        $quote->transport_fee           = $data['transport_fee'];
        $quote->insurance_fee           = $data['insurance_fee'];
        $quote->options_fee             = $data['options_fee'];
        $quote->fuel_fee                = ($data['fuel_fee'] == -1) ? "0.00" : $data['fuel_fee'];
        $quote->rental_fee              = $data['rental_fee'];
        $quote->subcharges_fee          = $data['subcharges_fee'];
        $quote->discount                = $data['discount'];
        $quote->voucher                 = $data['voucher'];
        $quote->total                   = $data['total'];
        // $quote->total_net               = $data['total_net'];
        // $quote->total_paid              = $data['total_paid'];
        // $quote->vat                     = $data['vat'];
        // $quote->vat_fee                 = $data['vat_fee'];
        $quote->balance                 = $data['balance'];

        $quote->flight                  = $data['flight'];
        $quote->phone                   = $data['phone'];
        $quote->extra_day               = ($data['extra_day']) ? '1' : '0';
        $quote->vat_included            = $data['vat_included'] ? true : false;

        if (isset($data['completed'])) {
            $quote->completed_at = Carbon::now();
        }

        $quote->save();

        return $quote;
    }

    public function create(Request $data, $lng)
    {
        $validator = $this->create_validator($data->all());
        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $quote = $this->create_quote($data, $data['id']);
        $quote->addTags();//needed for merge for v1
        $quote->addOptionsFromRequest();//needed for merge for v1
        $quote->handleDrivers();// needed for merge for v1
        $quote->addDocuments();// needed for merge for v1

        $new = true;
        if (!$quote->wasRecentlyCreated) {
            $new = false;
        }

        if ($data['create_next'] == 1) {
            return redirect()->route('create_booking_view', ['quote_id' => $quote->id, 'locale' => $lng]);
        } else if ($data['save'] == 'save') {
            return redirect()->route('edit_quote_view', [
                'cat_id' => $quote->id,
                'locale' => $lng,
                'new'    => $new
            ]);
        } else if ($data['save'] == 'save_and_new') {
            return redirect()->route('edit_quote_view', ['locale' => $lng]);
        } else {
            return redirect()->route('quotes', ['locale' => $lng]);
        }
    }

    public function delete_api(Request $data)
    {
            $offer = Quote::find($data['id']);// v2 sends one by one
            $offer->delete();
            return new QuoteResource($offer);
    }

    public function delete(Request $data)
    {
        $cars = Quote::whereIn('id', $data['ids'])->delete();
        return "Deleted";
    }

    public function edit($id)
    {
        $offer = Quote::find($id);

        return new QuoteResource($offer);
    }

    public function create_api(Request $data)
    {
        $validator = $this->create_validatorApi($data->all());
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $quote = $this->create_quote_api($data);
        $quote->addTags2();
        $quote->addOptionsFromRequest();
        $quote->handleDrivers();
        $quote->save();
        $quote->syncDocuments($data['documents']);
        $quote->save();
        // $quote->documents()->sync($data['documents']);

        $new = true;
        if (!$quote->wasRecentlyCreated) {
            $new = false;
        }

        $quote = Quote::find($quote->id);// needed for documents to load

        return new QuoteResource($quote);
    }

    public function create_quote_api($data)
    {
        $quote = Quote::firstOrNew(['id' => $data['id']]);

        $quote->customer_id             = $data['customer_driver.id'];

        if ($data['customer_driver.id']) {
            $quote->customer_text         = Driver::find($data['customer_driver.id'])->getFullName();
        } else {
            $quote->customer_text = $data['customer_driver.name'];
        }

        $quote->user_id                 = $quote->exists ? $quote->user_id : Auth::user()->id;
       // $quote->company_id              = $data['company_id'];
        if (is_array($data['company_id'])) {
            $quote->company_id               = (int)$data['company_id.id'];
        } else {
            $quote->company_id               = $data['company_id'];
        }


        $quote->type_id                 = $data['type_id'];
        $quote->brand_id                = $data['brand_id'];
        $quote->agent_id                = $data['agent_id'];
        $quote->sub_account_id          = $data['sub_account_id'];
        $quote->sub_account_type        = $data['sub_account_type'];
        $quote->source_id               = $data['source_id'];
        $quote->program_id              = $data['program_id'];

        $quote->checkout_station_id     = $data['checkout_station_id'];
        $quote->checkin_station_id      = $data['checkin_station_id'];

        $quote->checkout_place_id       = $data['checkout_place.id'];
        $quote->checkout_place_text     = $data['checkout_place.name'];

        $quote->checkin_place_id        = $data['checkin_place.id'];
        $quote->checkin_place_text      = $data['checkin_place.name'];

        $quote->charge_type_id          = $data['summary_charges.charge_type_id'];

        //$quote->confirmation_number     = $data['confirmation_number'];
        // $quote->agent_voucher           = $data['agent_voucher'];

        $quote->checkout_datetime       = $data['checkout_datetime'];
        $quote->checkin_datetime        = $data['checkin_datetime'];

        $quote->duration                = $data['duration'];

        $quote->valid_date
            = Carbon::parse($data['valid_date'])->format('Y-m-d H:i:s');


        $quote->excess                  = $data['excess'];
        $quote->discount_percentage     = $data['summary_charges.discount'] ?? 0; //discount percentage
        // $quote->checkout_station_fee    = $data['checkout_station_fee'];
        // $quote->checkin_station_fee     = $data['checkin_station_fee'];

        $quote->checkout_comments       = $data['checkout_comments'];
        $quote->checkin_comments        = $data['checkin_comments'];
        $quote->estimated_km            = $data['estimated_km'];
        $quote->extension_rate          = $data['extension_rate'];
        $quote->may_extend              = $data['may_extend'] == '1' ? '1' : '0';
        $quote->comments                = $data['comments'];

        $quote->rate                    = $data['summary_charges.rate'];
        $quote->distance_rate           = $data['summary_charges.distance_rate'];
        $quote->distance                = $data['summary_charges.distance'];

        $quote->transport_fee           = $data['summary_charges.transport_fee'];
        $quote->insurance_fee           = $data['summary_charges.insurance_fee'];
        $quote->options_fee             = $data['summary_charges.options_fee'];
        $quote->fuel_fee                = $data['summary_charges.fuel_fee'];
        $quote->rental_fee              = $data['summary_charges.rental_fee'];
        $quote->subcharges_fee          = $data['summary_charges.subcharges_fee'];
        $quote->discount                = $data['summary_charges.discount'];
        $quote->voucher                 = $data['summary_charges.voucher'];
        $quote->total                   = $data['summary_charges.total'];
        // $quote->total_net               = $data['total_net'];
        // $quote->total_paid              = $data['total_paid'];
        // $quote->vat                     = $data['vat'];
        // $quote->vat_fee                 = $data['vat_fee'];
        $quote->balance                 = $data['summary_charges.balance'];
        $quote->vat_included            = $data['summary_charges.vat_included'] ? true : false;

        $quote->flight                  = $data['flight'];
        $quote->phone                   = $data['phone'];
        $quote->extra_day               = $data['extra_day'] == '1' ? '1' : '0';

        if ($data['status']) {
            $quote->status            = $data['status'];
            $quote->cancel_reason_id  = $data['cancel_reason_id'];
        }


        if (isset($data['completed'])) {
            $quote->completed_at = Carbon::now();
        }

        $quote->save();

        return $quote;
    }
}