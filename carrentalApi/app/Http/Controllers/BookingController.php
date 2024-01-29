<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\BookingCollection;
use App\Http\Resources\BookingResource;
use App\Http\Resources\CancelReasonCollection;
use App\Http\Resources\CancelReasonResource;
use App\Models\Booking;
use App\Models\CancelReason;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Driver;
use App\Models\Language;
use App\Models\Quote;
use App\Models\Station;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $bookings = Booking::query();
        if ($term) {
            $bookings = $bookings->where('sequence_number', 'like', '%' . $term . '%')
                ->orWhere('customer_text', 'like', '%' . $term . '%')
                ->orWhere('confirmation_number', $term)
                ->orWhere('agent_voucher', $term)
                ->orWhere('phone', $term)
                ->orWhereHasMorph('customer', [Driver::class], function ($driver_q) use ($term) {
                    $driver_q->whereHas('contact', function ($contact_q) use ($term) {
                        $contact_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%")
                            ->orWhere('email', $term)->orWhere('phone', $term);
                    });
                })
                ->orWhereHasMorph('customer', [Contact::class], function ($driver_q) use ($term) {
                    $driver_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%")
                        ->orWhere('email', $term)->orWhere('phone', $term);
                });
        }

        $bookings = $bookings->filter($data)->applyOrder($data);

        if ($data->has('export')) {
            $filename = 'bookings';
            if ($data->checkout_station_id) {
                $filename .= '-CO:' . Station::find($data->checkout_station_id)->profile_title;
            }
            if ($data->checkout_datetime['from']) {
                $filename .= '-COFROM:' . $data->checkout_datetime['from'];
            }
            if ($data->checkout_datetime['to']) {
                $filename .= '-COTO:' . $data->checkout_datetime['to'];
            }
            if ($data->checkin_station_id) {
                $filename .= '-CI:' . Station::find($data->checkout_station_id)->profile_title;
            }
            if ($data->checkin_datetime['from']) {
                $filename .= '-CIFROM:' . $data->checkin_datetime['from'];
            }
            if ($data->checkin_datetime['to']) {
                $filename .= '-CITO:' . $data->checkin_datetime['to'];
            }
            return ExportController::createFileFromCollection($bookings->get(), $data['export-field'] ?? [], $filename);
        }

        $bookings = $bookings->paginate(Cookie::get('pages') ?? '5');
        return view('bookings.preview', compact(['bookings', 'lng']));
    }

    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $bookings = Booking::query()->orderBy('created_at', 'desc');;
        if ($term) {
            $bookings = $bookings->where('sequence_number', 'like', '%'.$term.'%')
                ->orWhere('customer_text', 'like', '%'.$term.'%')
                ->orWhere('confirmation_number', $term)
                ->orWhere('agent_voucher', $term)
                ->orWhere('phone', $term)
                ->orWhereHasMorph('customer', [Driver::class], function ($driver_q) use ($term) {
                    $driver_q->whereHas('contact', function ($contact_q) use ($term) {
                            $contact_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%")
                                ->orWhere('email', $term)->orWhere('phone', $term);
                        });
                })
                ->orWhereHasMorph('customer', [Contact::class], function ($driver_q) use ($term) {
                    $driver_q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like', "%" . $term . "%")
                        ->orWhere('email', $term)->orWhere('phone', $term);
                });
        }

         return new BookingCollection($bookings->filter($data)->applyOrder($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }

    public function create_view(Request $data, $lng)
    {

        if (isset($data['cat_id'])) {

            $booking = Booking::find($data['cat_id']);
            $printings = [$booking->last_printing];
            foreach ($booking->payments as $payment) {
                $printings[] = $payment->last_printing;
            }

            return view('bookings.create', [
                'lang'           => Language::all(),
                'booking'        => $booking,
                'lng'            => $lng,
                'duplicate'      => $data->has('duplicate') && $data->duplicate == true,
                'printings'      => $printings,
                'new'            => $data->new ? $data->new : false
            ]);
        } elseif ($data['quote_id']) {
            return view('bookings.create', [
                'lang'           => Language::all(),
                'booking'        => Quote::find($data['quote_id']),
                'lng'            => $lng,
                'duplicate'      => true
            ]);
        }
        return view('bookings.create', ['lang' => Language::all(), 'lng' => $lng, 'duplicate' => true]);
    }

    public function delete(Request $data)
    {
        $cars = Booking::whereIn('id', $data['ids'])->delete();
        return "Deleted";
    }


    public function delete_api(Request $data)
    {
            $booking = Booking::find($data['id']); //v2 sends one by one requests
            $booking->delete();
            return new BookingResource($booking);
    }


    public function edit($id) {
        $booking = Booking::find($id);
        return new BookingResource($booking);
    }

    public function reason(Request $request)
    {
        $reason = CancelReason::query();
        return new CancelReasonCollection($reason->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function reasonGetOne(Request $request,$id)
    {
            if($id==0){
                return 0;
            }
        else{
            $reason =CancelReason::find($id);
            return new CancelReasonResource($reason);
        }
    }

    public function create_validator($data) {
        $validator = Validator::make($data, [
            'duration' => 'required',
            'rate'     => 'required',
            'checkout_datetime'  => 'required|date',
            'checkout_station_id'  => 'required|exists:stations,id',
            'checkin_datetime'  => 'required|date',
            'checkin_station_id'  => 'required|exists:stations,id',
            'quote_id' => 'nullable|unique:bookings'
        ]);

        return $validator;
    }

    public function create_validatorApi($data,$id)
    {
        $validator = Validator::make($data, [
            'duration' => 'required',
            'summary_charges.rate'  => 'required',
            'summary_charges.charge_type_id'   => 'required',
            'customer_driver.name'=>'required',
            'checkout_datetime'  => 'required|date',
            'checkout_station_id'  => 'required|exists:stations,id',
            'checkin_datetime'  => 'required|date',
            'checkin_station_id'  => 'required|exists:stations,id',
            'quote_id' => "nullable|unique:bookings,quote_id,$id"
        ]);

        return $validator;
    }

    public function create_booking($data, $id = null) {
        $booking = Booking::firstOrNew(['id' => $id]);
        $customer_id = null;
        $customer_type = null;
        if ($data['customer_id']) {
            $customer = explode('-', $data['customer_id']);
            $customer_id = $customer[1];
            $customer_type = $customer[0];
        }

        $booking->quote_id              = $booking->exists ? $booking->quote_id : $data['quote_id'];
        $booking->customer_id           = $customer_id;
        $booking->customer_type         = $customer_type;
        $booking->customer_text         = $data['customer_text'];
        $booking->company_id            = $data['company_id'];
        $booking->user_id               = $booking->exists ? $booking->user_id : Auth::user()->id;
        $booking->type_id               = $data['type_id'];
        $booking->brand_id              = $data['brand_id'];
        $booking->vehicle_id            = $data['vehicle_id'];
        $booking->charge_type_id        = $data['charge_type_id'];
        $booking->agent_id              = $data['agent_id'];
        $booking->sub_account_id        = $data['sub_account_id'];
        $booking->sub_account_type      = $data['sub_account_type'];
        $booking->source_id             = $data['source_id'];
        $booking->program_id            = $data['program_id'];
        $booking->duration              = $data['duration'];
        $booking->rate                  = $data['rate'];
        $booking->distance              = $data['distance'];
        $booking->distance_rate         = $data['distance_rate'];
        $booking->extension_rate        = $data['extension_rate'];
        $booking->may_extend            = ($data['may_extend'])?'1':'0';

        $booking->confirmation_number   = $data['confirmation_number'];
        $booking->agent_voucher         = $data['agent_voucher'];

        $booking->checkout_datetime     = $data['checkout_datetime'];
        $booking->checkout_station_id   = $data['checkout_station_id'];
        $booking->checkout_place_id     = $data['checkout_place_id'];
        $booking->checkout_place_text   = $data['checkout_place_text'];
        $booking->checkout_station_fee  = $data['checkout_station_fee'];
        $booking->checkout_comments     = $data['checkout_notes'];

        $booking->checkin_datetime      = $data['checkin_datetime'];
        $booking->checkin_station_id    = $data['checkin_station_id'];
        $booking->checkin_place_id      = $data['checkin_place_id'];
        $booking->checkin_place_text    = $data['checkin_place_text'];
        $booking->checkin_station_fee   = $data['checkin_station_fee'];
        $booking->checkin_comments      = $data['checkin_notes'];
        $booking->comments              = $data['notes'];

        $booking->excess                = $data['excess'];
        $booking->transport_fee         = $data['transport_fee'];
        $booking->insurance_fee         = $data['insurance_fee'];
        $booking->options_fee           = $data['options_fee'];
        $booking->fuel_fee              = ($data['fuel_fee']==-1)?"0.00":$data['fuel_fee'];
        $booking->subcharges_fee        = $data['subcharges_fee'];
        $booking->rental_fee            = $data['rental_fee'];
        $booking->discount              = $data['discount'];
        $booking->voucher               = $data['voucher'];
        $booking->total                 = $data['total'];
        $booking->total_net             = $data['total_net'];
        $booking->total_paid            = $data['total_paid'];
        $booking->vat                   = $data['vat'];
        $booking->vat_fee               = $data['vat_fee'];
        $booking->balance               = $data['balance'];
        $booking->vat_included          = $data['vat_included'] ? true : false;

        $booking->flight                = $data['flight'];
        $booking->phone                 = $data['phone'];
        $booking->extra_day             = ($data['extra_day'])?'1':'0';
        if ($data['status']) {
            $booking->status            = $data['status'];
            $booking->cancel_reason_id  = $data['cancel_reason_id'];
        }
        $booking->save();

        return $booking;
    }

    public function create(Request $data, $lng)
    {
        $validator = $this->create_validator($data->all());
        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }
        $booking = $this->create_booking($data, $data['id']);
        $booking->addPaymentsFromRequest();//needed for merge for v1
        $booking->addTags();//needed for merge for v1
        $booking->addOptionsFromRequest();//needed for merge for v1
        $booking->handleDrivers();//needed for merge for v1
        $booking->addDocuments();//needed for merge for v1


        if ($booking->quote_id && $data->quote_id) {
            $quote = $booking->quote()->first();
            $quote->status = Quote::STATUS_BOOKING;
            $quote->save();
        }

        $new = true;
        if (!$booking->wasRecentlyCreated) {
            $new = false;
        }

        if ($data['create_next'] == 1) {
            return redirect()->route('create_rental_view', ['booking_id' => $booking->id, 'locale' => $lng,]);
        } else if ($data['save'] == 'save') {
            return redirect()->route('create_booking_view', [
                'cat_id'    => $booking->id,
                'locale'    => $lng,
                'new'       => $new
            ]);
        } else if ($data['save'] == 'save_and_new') {
            return redirect()->route('create_booking_view', ['locale' => $lng]);
        } else {
            return redirect()->route('bookings', ['locale' => $lng]);
        }
    }


    public function create_booking_api($data)
    {
        $booking = Booking::firstOrNew(['id' => $data['id']]);
        $booking->quote_id              = $booking->exists ? $booking->quote_id : $data['quote_id'];
        $booking->customer_id           = $data['customer_driver.id'];
        $booking->customer_type         = ucwords('App\\Driver');

        if($data['customer_driver.id']){
        $booking->customer_text         = Driver::find($data['customer_driver.id'])->getFullName();
        }
        else{
        $booking->customer_text =$data['customer_driver.name'];
        }
        $booking->company_id            = $data['company_id'];

        $booking->user_id               = $booking->exists ? $booking->user_id : Auth::user()->id;
        $booking->type_id               = $data['type_id'];
        $booking->brand_id              = $data['brand_id'];
        $booking->vehicle_id            = $data['vehicle_id'];
        $booking->charge_type_id        = $data['summary_charges.charge_type_id'];
        $booking->agent_id              = $data['agent_id'];
        $booking->sub_account_id        = $data['sub_account_id'];
        $booking->sub_account_type      = $data['sub_account_type'];
        $booking->source_id             = $data['source_id'];
        $booking->program_id            = $data['program_id'];
        $booking->duration              = $data['duration'];

        $booking->rate                  = $data['summary_charges.rate'];
        $booking->distance              = $data['summary_charges.distance'];
        $booking->distance_rate         = $data['summary_charges.distance_rate'];
        $booking->extension_rate        = $data['extension_rate'];
        $booking->may_extend            = $data['may_extend']=='1' ? '1' : '0';

        $booking->confirmation_number   = $data['confirmation_number'];
        $booking->agent_voucher         = $data['agent_voucher'];

        $booking->checkout_datetime     = $data['checkout_datetime'];
        $booking->checkout_station_id   = $data['checkout_station_id'];
        $booking->checkout_place_id     = $data['checkout_place.id'];
        $booking->checkout_place_text   = $data['checkout_place.name'];
       // $booking->checkout_station_fee  = $data['checkout_station_fee'];
        $booking->checkout_comments     = $data['checkout_comments'];

        $booking->checkin_datetime      = $data['checkin_datetime'];
        $booking->checkin_station_id    = $data['checkin_station_id'];
        $booking->checkin_place_id      = $data['checkin_place.id'];
        $booking->checkin_place_text    = $data['checkin_place.name'];
       // $booking->checkin_station_fee   = $data['checkin_station_fee'];
        $booking->checkin_comments      = $data['checkin_comments'];
        $booking->comments              = $data['comments'];

        $booking->excess                = $data['excess'];

        $booking->transport_fee         = $data['summary_charges.transport_fee'];
        $booking->insurance_fee         = $data['summary_charges.insurance_fee'];
        $booking->options_fee           = $data['summary_charges.options_fee'];
        $booking->fuel_fee              = $data['summary_charges.fuel_fee'];
        $booking->subcharges_fee        = $data['summary_charges.subcharges_fee'];
        $booking->rental_fee            = $data['summary_charges.rental_fee'];
        $booking->discount              = $data['summary_charges.discount'];
        $booking->voucher               = $data['summary_charges.voucher'];
        $booking->total                 = $data['summary_charges.total'];
        $booking->total_net             = $data['summary_charges.total_net'];
        $booking->total_paid            = $data['summary_charges.total_paid'];
        $booking->vat                   = $data['summary_charges.vat'];
        $booking->vat_fee               = $data['summary_charges.vat_fee'];
        $booking->balance               = $data['summary_charges.balance'];
        $booking->vat_included          = $data['summary_charges.vat_included'] ? true : false;

        $booking->flight                = $data['flight'];
        $booking->phone                 = $data['phone'];
        $booking->extra_day             = $data['extra_day'] == '1' ? '1' : '0';
        if ($data['status']) {
            $booking->status            = $data['status'];
            $booking->cancel_reason_id  = $data['cancel_reason_id'];
        }

        $booking->save();

        return $booking;
    }


    public function create_api(Request $data)
    {
        $validator = $this->create_validatorApi($data->all(),$data['id']);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $booking = $this->create_booking_api($data);
        $booking->addPaymentsFromRequest2();
        $booking->addTags2();
        $booking->addOptionsFromRequest();
        $booking->handleDrivers();
       // $booking->addDocuments();
        //$booking->documents()->sync($data['documents']);
        if($data['convert']==true){
          $quoteDocsLinks= DB::table('document_links')->where('document_link_id', $data->quote_id)->get();
          foreach($quoteDocsLinks as $links){
            $currentDoc = Document::find($links->document_id);
            if($currentDoc->document_type!='print'){
            DB::table('document_links')->where('id', $links->id)->update(['document_link_type'=>'App\\Booking',
            'document_link_id' => $booking->id]);
            }
          }
        }
        else{
            $booking->syncDocuments($data['documents']);
        }

        $booking->save();

        if ($booking->quote_id && $data->quote_id) {
            $quote = $booking->quote()->first();
            $quote->status = Quote::STATUS_BOOKING;
            $quote->save();
        }

        $new = true;
        if (!$booking->wasRecentlyCreated) {
            $new = false;
        }

        $booking = Booking::find($booking->id);// needed for documents to load

        return new BookingResource($booking);
    }
}
