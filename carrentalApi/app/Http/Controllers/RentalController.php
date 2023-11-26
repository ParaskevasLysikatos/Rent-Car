<?php

namespace App\Http\Controllers;

use Auth;
use Cookie;
use Session;
use Validator;
use App\Driver;
use App\Rental;
use App\Booking;
use App\Http\Resources\RentalCollection;
use App\Http\Resources\RentalResource;
use App\Language;
use App\Station;
use App\Transaction;
use Illuminate\Http\Request;
use App\Document;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{

    public function preview(Request $request, $lng)
    {
        $term = $request['search'];
        $rentals = Rental::query();

        if ($term) {
            $rentals = $this->searchModel($request);
        }

        $rentals = $rentals->filter($request)->applyOrder($request);

        if (!$rentals->getQuery()->orders) {
            $rentals = $rentals->orderBy('created_at', 'ASC');
        }

        if ($request->has('export')) {
            $filename = 'rentals';
            if ($request->checkout_station_id) {
                $filename .= '-CO:' . Station::find($request->checkout_station_id)->profile_title;
            }
            if ($request->checkout_datetime['from']) {
                $filename .= '-COFROM:' . $request->checkout_datetime['from'];
            }
            if ($request->checkout_datetime['to']) {
                $filename .= '-COTO:' . $request->checkout_datetime['to'];
            }
            if ($request->checkin_station_id) {
                $filename .= '-CI:' . Station::find($request->checkout_station_id)->profile_title;
            }
            if ($request->checkin_datetime['from']) {
                $filename .= '-CIFROM:' . $request->checkin_datetime['from'];
            }
            if ($request->checkin_datetime['to']) {
                $filename .= '-CITO:' . $request->checkin_datetime['to'];
            }
            return ExportController::createFileFromCollection($rentals->get(), $request['export-field'] ?? [], $filename);
        }

        $rentals = $rentals->paginate(Cookie::get('pages') ?? '5');
        return view('rentals.preview', compact(['rentals', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $term = $request['search'];
        $rentals = Rental::query()->orderBy('created_at', 'desc');

        if ($term) {
            $rentals = $this->searchModel($request);
        }

        $rentals = $rentals->filter($request)->applyOrder($request);

        if(!$rentals->getQuery()->orders) {
            $rentals = $rentals->orderBy('created_at', 'ASC');
        }

        return new RentalCollection($rentals->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));

    }

    public function edit(Request $request, $id) {
        $rental = Rental::find($id);

        return new RentalResource($rental);
    }

    public function searchModel(Request $data)
    {
        $term = $data['search'];

        $rentals  = Rental::where(function ($q) use ($term) {
            $q->where('sequence_number', 'like', '%' . $term . '%')
                ->orWhere('confirmation_number', $term)
                ->orWhere('agent_voucher', $term)
                ->orWhere('manual_agreement', 'like', '%' . $term . '%')
                ->orWhere('rentals.id', 'like', '%' . $term . '%')
                ->orWhereHas('vehicle', function ($vehicle_q) use ($term) {
                    $vehicle_q->whereHas('license_plates', function ($licence_q) use ($term) {
                        $licence_q->where('licence_plate', 'like', '%' . $term . '%');
                    });
                })
                ->orWhereHas('exchanges', function ($exchange_q) use ($term) {
                    $exchange_q->whereHas('old_vehicle', function ($vehicle_q) use ($term) {
                        $vehicle_q->whereHas('license_plates', function ($licence_q) use ($term) {
                            $licence_q->where('licence_plate', 'like', '%' . $term . '%');
                        });
                    });
                })
                ->orWhereHas('driver.contact', function ($driver_q) use ($term) {
                    $driver_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%")
                        ->orWhere('email', $term)->orWhere('phone', $term);
                })
                ->orWhereHas('company', function ($company_q) use ($term) {
                    $company_q->where('name', 'like', '%' . $term . '%')->orWhere('afm', $term);
                })
                ->orWhereHas('agent.company', function ($company_q) use ($term) {
                    $company_q->where('name', 'like', '%' . $term . '%')->orWhere('afm', $term);
                });
        });
        if (isset($data['invoice']) && !$data['invoice']) {
            $rentals = $rentals->where(function ($query) {
                $query->whereDoesntHave('invoices')->orWhereHas('invoices', function ($q) {
                    $q->where('invoices.final_total', '!=', DB::raw('total'));
                });
            });
        }
        if (isset($data['transactor']) && isset($data['transactor_type']) && $data['transactor']) {
            $rentals = $rentals->whereHas('transactions', function ($q) use ($data) {
                $q->where('transactor_id', $data['transactor'])->where('transactor_type', $data['transactor_type']);
            });
        }

        if (isset($data['status']) && $data['status']) {
            $rentals = $rentals->whereIn('status', $data['status']);
        }

        return $rentals;
    }

    public function search(Request $data, $lng) {
        $rentals = $this->searchModel($data)->paginate(Cookie::get('pages') ?? 10);
        return view('rentals.preview', compact(['rentals', 'lng']));
    }

    public function search_ajax(Request $data){
        $rentals = $this->searchModel($data)->take(Cookie::get('pages') ?? 10)->get();
        return response()->json($rentals);
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {;
            $rental = Rental::find($data['cat_id']);
            $printings = [$rental->last_printing];
            foreach ($rental->payments as $payment) {
                $printings[] = $payment->last_printing;
            }
            foreach ($rental->invoices as $invoice) {
                $printings[] = $invoice->last_printing;
            }
            return view('rentals.create', [
                'lang'          => Language::all(),
                'rental'        => $rental,
                'lng'           => $lng,
                'duplicate'     => $data->has('duplicate') && $data->duplicate == true,
                'printings'     => $printings,
                'new'           => $data->new ? $data->new : false
            ]);
        } elseif ($data['booking_id']) {
            return view('rentals.create', [
                'lang'          => Language::all(),
                'rental'        => Booking::find($data['booking_id']),
                'lng'           => $lng,
                'duplicate'     => false
            ]);
        }
        return view('rentals.create', ['lang' => Language::all(), 'lng' => $lng, 'duplicate' => true]);
    }


    public function delete(Request $data)
    {
        $cars = Rental::whereIn('id', $data['ids'])->delete();
        return "Deleted";
    }

    public function delete_api(Request $data)
    {
            $rental = Rental::find($data['id']);// v2 sends one by one
            $rental->delete();
            return new RentalResource($rental);
    }

    public function create_validator($data)
    {
        $validator = Validator::make($data, [
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
        ]);

        return $validator;
    }


    public function create_validator_api($data,$id)
    {
        $validator = Validator::make($data, [
            'duration'              => 'required',
            'summary_charges.rate'   => 'required', // on frontend
            'summary_charges.charge_type_id'   => 'required',
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
            'booking_id'            => "nullable|unique:rentals,booking_id,$id"
        ]);

        return $validator;
    }

    public function update(Request $request) {
        $rental = $this->create_rental_api($request);

        return new RentalResource($rental);
    }

    public function create_rental_api(Request $data) {
        $rental = Rental::firstOrNew(['id' => $data['id']]);

        $rental->company_id            = $data['company_id'];
        $rental->user_id               = $rental->exists ? $rental->user_id : Auth::user()->id;
        $rental->booking_id            = $rental->exists ? $rental->booking_id : $data['booking_id'];
        $rental->type_id               = $data['type_id'];
        $rental->brand_id              = $data['brand_id'];
        $rental->vehicle_id            = $data['vehicle_id'];
        $rental->charge_type_id        = $data['summary_charges.charge_type_id'];
        $rental->sub_account_id        = $data['sub_account_id'];
        $rental->sub_account_type      = $data['sub_account_type'];
        $rental->source_id             = $data['source_id'];
        $rental->driver_id             = $data['driver_id'];
        $rental->program_id            = $data['program_id'];
        $rental->agent_id              = $data['agent_id'];
        $rental->rate                  = $data['summary_charges.rate'];
        $rental->distance              = $data['summary_charges.distance'];
        $rental->distance_rate         = $data['summary_charges.distance_rate'];
        $rental->extension_rate        = $data['extension_rate'];
        $rental->may_extend            = $data['may_extend']=='1' ? '1' : '0';

        $rental->confirmation_number   = $data['confirmation_number'];
        $rental->agent_voucher         = $data['agent_voucher'];

        $rental->checkout_datetime     = $data['checkout_datetime'];
        $rental->checkout_station_id   = $rental->exists ? $rental->checkout_station_id : $data['checkout_station_id'];
        $rental->checkout_place_id     = $data['checkout_place.id'];
        $rental->checkout_place_text   = $data['checkout_place.name'];
       // $rental->checkout_station_fee  = $data['checkout_station_fee'];
        $rental->checkout_comments     = $data['checkout_comments'];

        if (!$rental->exists) {
            $rental->booked_checkin_datetime = $data['checkin_datetime'];
        }
        $rental->checkin_datetime      = $data['checkin_datetime'];
        $rental->checkin_station_id    = $data['checkin_station_id'];
        $rental->checkin_place_id      = $data['checkin_place.id'];
        $rental->checkin_place_text    = $data['checkin_place.name'];
       // $rental->checkin_station_fee   = $data['checkin_station_fee'];
        $rental->checkin_comments      = $data['checkin_comments'];
        $rental->comments              = $data['comments'];

        $rental->excess                = $data['excess'];
        $rental->transport_fee         = $data['summary_charges.transport_fee'];
        $rental->insurance_fee         = $data['summary_charges.insurance_fee'];
        $rental->options_fee           = $data['summary_charges.options_fee'];
        $rental->fuel_fee              = $data['summary_charges.fuel_fee'];
        $rental->subcharges_fee        = $data['summary_charges.subcharges_fee'];
        $rental->rental_fee            = $data['summary_charges.rental_fee'];
        $rental->discount              = $data['summary_charges.discount'] ? $data['summary_charges.discount']:0;
        $rental->voucher               = $data['summary_charges.voucher'];
        $rental->total                 = $data['summary_charges.total'];
        $rental->total_net             = $data['summary_charges.total_net'] ?? 0;
        $rental->total_paid            = $data['summary_charges.total_paid'];
        $rental->vat                   = $data['summary_charges.vat'] ? $data['summary_charges.vat'] : 0;
        $rental->vat_fee               = $data['summary_charges.vat_fee'];
        $rental->balance               = $data['summary_charges.balance'];

        $rental->checkout_driver_id    = $data['checkout_driver_id'] ?? Auth::user()->driver_id;
        $rental->checkout_km           = $data['checkout_km'];
        $rental->checkout_fuel_level   = $data['checkout_fuel_level'];

        $rental->checkin_km            = $data['checkin_km'];
        $rental->checkin_fuel_level    = $data['checkin_fuel_level'];

        $rental->flight                = $data['flight'];
        $rental->manual_agreement      = $data['summary_charges.manual_agreement'];
        $rental->extra_day             = $data['extra_day'] == '1' ? '1' : '0';
        $rental->duration              = $data['duration'];



        $rental->checkin_driver_id = null;
        if ($data['status'] && in_array($data['status'], Rental::getValidStatuses())) {
            $rental->status            = $data['status'];
            $checkin_driver_statuses = [Rental::STATUS_CHECKED_IN, Rental::STATUS_PRE_CHECKED_IN, Rental::STATUS_COMPLETED, Rental::STATUS_CLOSED];
            if (in_array($data['status'], $checkin_driver_statuses)) {
                $rental->checkin_driver_id = $data['checkin_driver_id'];
            }
        }
        $rental->save();

        return $rental;

    }

    public function create_api(Request $data){
        $fail = false;
        if ($data->status == Rental::STATUS_CLOSED && $data->checkin_datetime > now()->addDay()->format('Y-m-d')) {
            $fail = true;
        }
        $validator = $this->create_validator_api($data->all(),$data['id']);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        if ($fail) {
            return response()->json('checkin_datetime > now + 1', 400);
        }
        $rental = $this->create_rental_api($data);
        $rental->addPaymentsFromRequest2();
        $rental->addTags2();
        $rental->addOptionsFromRequest();
        $rental->handleDrivers();
        //$rental->addDocuments();
        //$rental->documents()->sync($data['documents']);
        if ($data['convert'] == true) {
            $bookingDocsLinks = DB::table('document_links')->where('document_link_id', $data->booking_id)->get();
            foreach ($bookingDocsLinks as $links) {
                $currentDoc = Document::find($links->document_id);
                if ($currentDoc->document_type != 'print') {
                    DB::table('document_links')->where('id', $links->id)->update([
                        'document_link_type' => 'App\\Rental',
                        'document_link_id' => $rental->id
                    ]);
                }
            }
        } else {
            $rental->syncDocuments($data['documents']);
        }

        if ($data->booking_id && $rental->booking_id) {
            $booking = $rental->booking()->first();
            $booking->status = Booking::STATUS_RENTAL;
            $booking->save();

            $rental->payments()->attach($booking->payments->pluck('id'));
           // $rental->documents()->attach($booking->documents->pluck('id'));

            foreach ($rental->payments as $payment) {
                if (!$payment->payer_id) {
                    $payment->payer_id = $rental->driver_id;
                    $payment->payer_type = Driver::class;
                    $payment->save();
                }
            }

            $transactions = Transaction::where('booking_id', $rental->booking_id)->get();
            foreach ($transactions as $tr) {
                $tr->delete();
            }
        }


        $new = true;
        if (!$rental->wasRecentlyCreated) {
            $new = false;
        }

        $rental->save();

        $rental= Rental::find($rental->id);// needed for documents to load

        return new RentalResource($rental);
    }

    public function create_rental(Request $data) {
        $rental = Rental::firstOrNew(['id' => $data['id']]);

        $rental->company_id            = $data['company_id'];
        $rental->user_id               = $rental->exists ? $rental->user_id : Auth::user()->id;
        $rental->booking_id            = $rental->exists ? $rental->booking_id : $data['booking_id'];
        $rental->type_id               = $data['type_id'];
        $rental->brand_id              = $data['brand_id'];
        $rental->vehicle_id            = $data['vehicle_id'];
        $rental->charge_type_id        = $data['charge_type_id'];
        $rental->sub_account_id        = $data['sub_account_id'];
        $rental->sub_account_type      = $data['sub_account_type'];
        $rental->source_id             = $data['source_id'];
        $rental->driver_id             = $data['driver_id'];
        $rental->program_id            = $data['program_id'];
        $rental->agent_id              = $data['agent_id'];
        $rental->rate                  = $data['rate'];
        $rental->distance              = $data['distance'];
        $rental->distance_rate         = $data['distance_rate'];
        $rental->extension_rate        = $data['extension_rate'];
        $rental->may_extend            = ($data['may_extend'])?'1':'0';

        $rental->confirmation_number   = $data['confirmation_number'];
        $rental->agent_voucher         = $data['agent_voucher'];

        $rental->checkout_datetime     = $data['checkout_datetime'];
        $rental->checkout_station_id   = $rental->exists ? $rental->checkout_station_id : $data['checkout_station_id'];
        $rental->checkout_place_id     = $data['checkout_place_id'];
        $rental->checkout_place_text   = $data['checkout_place_text'];
        $rental->checkout_station_fee  = $data['checkout_station_fee'];
        $rental->checkout_comments     = $data['checkout_notes'];

        if (!$rental->exists) {
            $rental->booked_checkin_datetime = $data['checkin_datetime'];
        }
        $rental->checkin_datetime      = $data['checkin_datetime'];
        $rental->checkin_station_id    = $data['checkin_station_id'];
        $rental->checkin_place_id      = $data['checkin_place_id'];
        $rental->checkin_place_text    = $data['checkin_place_text'];
        $rental->checkin_station_fee   = $data['checkin_station_fee'];
        $rental->checkin_comments      = $data['checkin_notes'];
        $rental->comments              = $data['notes'];

        $rental->excess                = $data['excess'];
        $rental->transport_fee         = $data['transport_fee'];
        $rental->insurance_fee         = $data['insurance_fee'];
        $rental->options_fee           = $data['options_fee'];
        $rental->fuel_fee              = ($data['fuel_fee']==-1)?"0.00":$data['fuel_fee'];
        $rental->subcharges_fee        = $data['subcharges_fee'];
        $rental->rental_fee            = $data['rental_fee'];
        $rental->discount              = $data['discount'];
        $rental->voucher               = $data['voucher'];
        $rental->total                 = $data['total'];
        $rental->total_net             = $data['total_net'];
        $rental->total_paid            = $data['total_paid'];
        $rental->vat                   = $data['vat'];
        $rental->vat_fee               = $data['vat_fee'];
        $rental->balance               = $data['balance'];

        $rental->checkout_driver_id    = $data['checkout_driver_id'] ?? Auth::user()->driver_id;
        $rental->checkout_km           = $data['checkout_km'];
        $rental->checkout_fuel_level   = $data['checkout_fuel_level'];

        $rental->checkin_km            = $data['checkin_km'];
        $rental->checkin_fuel_level    = $data['checkin_fuel_level'];

        $rental->flight                = $data['flight'];
        $rental->manual_agreement      = $data['manual_agreement'];
        $rental->extra_day             = ($data['extra_day'])?'1':'0';
        $rental->duration              = $data['duration'];

        $rental->checkin_driver_id = null;
        if ($data['status'] && in_array($data['status'], Rental::getValidStatuses())) {
            $rental->status            = $data['status'];
            $checkin_driver_statuses = [Rental::STATUS_CHECKED_IN, Rental::STATUS_PRE_CHECKED_IN, Rental::STATUS_COMPLETED, Rental::STATUS_CLOSED];
            if (in_array($data['status'], $checkin_driver_statuses)) {
                $rental->checkin_driver_id = $data['checkin_driver_id'];
            }
        }
        $rental->save();

        return $rental;
    }

    public function create(Request $data, $lng)
    {
        $validator = $this->create_validator($data->all());
        $fail = false;
        if ($data->status == Rental::STATUS_CLOSED && $data->checkin_datetime > now()->addDay()->format('Y-m-d')) {
            $fail = true;
        }
        if ($validator->fails() || $fail) {
            Session::flash('message', $validator->fails() ? $validator->errors()->first() :
                'Το συμβόλαιο δεν μπορεί να κλείσει με μεταγενέστερη ημερομηνία από την ημερομηνία τιμολόγησης');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $rental = $this->create_rental($data);
        $rental->addPaymentsFromRequest();//needed for merge for v1
        $rental->addTags();//needed for merge for v1
        $rental->addOptionsFromRequest();//needed for merge for v1
        $rental->handleDrivers();// needed for merge for v1
        $rental->addDocuments();// needed for merge for v1

        if ($data->booking_id && $rental->booking_id) {
            $booking = $rental->booking()->first();
            $booking->status = Booking::STATUS_RENTAL;
            $booking->save();

            $rental->payments()->attach($booking->payments->pluck('id'));
            $rental->documents()->attach($booking->documents->pluck('id'));

            foreach ($rental->payments as $payment) {
                if (!$payment->payer_id) {
                    $payment->payer_id = $rental->driver_id;
                    $payment->payer_type = Driver::class;
                    $payment->save();
                }
            }

            $transactions = Transaction::where('booking_id', $rental->booking_id)->get();
            foreach ($transactions as $tr) {
                $tr->delete();
            }
        }

        $new = true;
        if (!$rental->wasRecentlyCreated) {
            $new = false;
        }

        if ($data['save'] == 'save') {
            return redirect()->route('create_rental_view' ,[
                'cat_id'=> $rental->id,
                'locale' => $lng,
                'new' => $new
            ]);
        } else if ($data['save'] == 'save_and_new') {
            return redirect()->route('create_rental_view', ['locale' => $lng, 'new' => $new]);
        } else {
            return redirect()->route('rentals', ['locale' => $lng, 'new' => $new]);
        }
    }
}