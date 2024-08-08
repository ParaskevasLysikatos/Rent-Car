<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentCardsResource;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\PaymentCollection;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview_api(Request $data,$payment_type,$lng=null)
    {
        if ($payment_type && !Payment::validType($payment_type)) {
            abort(404);
        }
        $term = $data['search'];
        $payments = Payment::query()->orderBy('created_at', 'desc');

        if ($payment_type) {
            $payments = $payments->where('payment_type', $payment_type);
        }

       if ($term) {
           $payments->where('id', 'like', $term)
               ->orWhere("sequence_number", "like", "%" . $term . "%");
       }


        $payments = $payments->filter($data);

            return new PaymentCollection($payments->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }


    public function preview(Request $data, $lng, $payment_type)
    {
        if (!Payment::validType($payment_type)) {
            abort(404);
        }
        $term = $data['search'];
        $payments = Payment::where('payment_type', $payment_type);

        if ($term) {
            $payments->where('id', 'like', "%" . $term . "%")
                ->orwhere("sequence_number", "like", "%" . $term . "%");
        }

        $payments = $payments->filter($data);
        $total = (clone $payments)->select(DB::raw('SUM(amount) as amount'))->first()->amount;

        $payments = $payments->paginate(Cookie::get('pages') ?? '5');
        return view('payments.preview', compact(['payments', 'total', 'lng', 'payment_type']));
    }


    public function search_ajax(Request $request, $lng, $payment_type)
    {
        $payments = Payment::query();
        $term = $request['search'];
        $payer_id = $request['payer_id'];
        $payer_type = $request['payer_type'];

        if ($payment_type) {
            $payments = $payments->where('payment_type', $payment_type);
        }

        if ($term) {
            $payments->where(function($q) use ($payment_type, $term) {
                $q->where('id', $term )
                    ->orWhere('sequence_number', 'like', '%'.$term.'%');
            });
        }

        if ($payer_id && $payer_type) {
            $payments = $payments->where('payer_id', $payer_id)->where('payer_type', $payer_type);
        }

        if ($request->has('invoice_id')) {
            $invoice_id = $request->invoice_id;
            if ($invoice_id) {
                $payments = $payments->whereHas('invoices', function ($q) use ($invoice_id) {
                    $q->where('invoices.id', $invoice_id);
                });
            } else {
                $payments = $payments->whereDoesntHave('invoices');
            }
        }

        if ($request->has('rental_id') && $rental_id = $request->rental_id) {
            $payments = $payments->where(function ($sub_q) use ($rental_id) {
                $sub_q->whereHas('rentals', function ($q) use ($rental_id) {
                    $q->where('rentals.id', $rental_id);
                })->orWhereDoesntHave('rentals');
            });
        }

        $payments = $payments->take(Cookie::get('pages') ?? '5')->get();
        return response()->json($payments);
    }

    public function create_view(Request $data, $lng, $payment_type)
    {
        $lng = $lng;
        if (isset($data['cat_id'])) {
            $payment = Payment::find($data['cat_id']);
            $printings = [$payment->last_printing];

            return view('payments.create', [
                'payment'           => $payment,
                'payment_type'      => $payment_type,
                'lng'               => $lng,
                'new'               => $data->new ? $data->new : false,
                'printings'         => $printings
            ]);
        } else if (isset($data['rental_id'])) {
            return view('payments.create', ['lng'=>$lng,'rental_id' => $data['rental_id'], 'payment_type' => $payment_type]);
        }
        return view('payments.create', ['lng'=>$lng, 'payment_type' => $payment_type]);
    }

    public function delete_api(Request $data)
    {
            $payment = Payment::find($data['id']);
            if (!$payment->invoice()) {
                $payment->delete();
            }
            return new PaymentResource($payment);
    }

    public function delete(Request $data)
    {
        foreach ($data['ids'] as $id) {
            $payment = Payment::find($id);
            if (!$payment->invoice()) {
                $payment->delete();
            }
        }
        return "ok";
    }

    public function edit(Request $request, $payment_type, $id) {
        $payment = Payment::find($id);

        return new PaymentResource($payment);
    }


    public function create_payment($data) {
        $validator = Validator::make($data, [
            'payment_datetime' => 'required|date|before:now',
            'payer_id' => 'nullable',
            'payer_type' => 'required',
            'balance' => 'nullable',
            'amount' => 'required',
            'reference' => 'nullable',
            'user_id' => 'required',
            // 'station' => 'required',
            'place' => 'nullable',
            // 'payment_method' => 'required',
            'comments' => 'nullable',
        ]);
        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return false;
        }

        $payment = Payment::firstOrNew(['id' => $data['id'] ?? null]);

        $payment->payment_datetime      = $data['payment_datetime'];
        $payment->user_id               = $data['user_id'];
        $payment->payer_id              = $data['payer_id'];
        $payment->payer_type            = $data['payer_type'];
        $payment->amount                = $data['amount'];
        $payment->station_id            = $data['station'] ?? $data['station_id'];
        $payment->balance               = $data['balance'];
        $payment->method                = isset($data['payment_method']) ? $data['payment_method'] : $data['method'];
        $payment->comments              = $data['comments'];
        $payment->reference             = $data['reference'];
        $payment->place_id              = $data['place_id'];
        $payment->place_text            = $data['place_text'];

        $credit_card = null;
        if (isset($data['credit_card_number'])) {
            $credit_card = '*************'.substr($data['credit_card_number'], -4);
        }
        $payment->credit_card_number    = $credit_card;
        $payment->credit_card_month     = $data['credit_card_month'] ?? '';
        $payment->credit_card_year      = $data['credit_card_year'] ?? '';
        $payment->cheque_number         = $data['cheque_number'] ?? '';
        $payment->cheque_due_date       = $data['cheque_due_date'] ?? '';
        $payment->bank_transfer_account = $data['bank_transfer_account'] ?? '';
        $payment->card_type             = $data['card_type'] ?? '';

        if (isset($data['brand_id'])) {
            $payment->brand_id = $data['brand_id'];
        }

        if (isset($data['payment_type']) && $data['payment_type']) {
            $payment->payment_type = $data['payment_type'];
        }
        $payment->foreigner             = $data['foreigner'] ?? 0;
        $payment->save();

        return $payment;
    }


    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'payment_datetime' => 'required|date|before:now',
            'payer.id' => 'nullable',
            'payer.type' => 'required',
            'balance' => 'nullable',
            'amount' => 'required',
            //'reference' => 'nullable',
            'user_id' => 'required',
            // 'station' => 'required',
            'place' => 'nullable',
            // 'payment_method' => 'required',
            'comments' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $payment = Payment::firstOrNew(['id' => $data['id'] ?? null]);

        $payment->payment_datetime      = $data['payment_datetime'];

        if (is_array($data['user_id'])) {
            $payment->user_id               = (int)$data['user_id.id'];
        } else {
            $payment->user_id               = $data['user_id'];
        }

        $payment->payer_id              = $data['payer.id'];
        $payment->payer_type            = 'App\\'.ucfirst($data['payer.type']);
        $payment->amount                = $data['amount'];
       //$payment->station_id            = $data['station'] ?? $data['station_id'];
        if (is_array($data['station_id'])) {
            $payment->station_id               = (int)$data['station_id.id'];
        } else {
            $payment->station_id               = $data['station_id'];
        }

        $payment->balance               = $data['balance'];
        $payment->method                = isset($data['payment_method']) ? $data['payment_method'] : $data['method'];
        $payment->comments              = $data['comments'];
        $payment->reference             = $data['reference'];
        // $payment->place_id              = $data['place.id'];
        // $payment->place_text            = $data['place.name'];
        if (isset($data['place.id'])) {
            $payment->place_id              = $data['place.id'];
            $payment->place_text            = $data['place.name'];
        } else {
            $payment->place_id              =  null;
            $payment->place_text            =  null;
        }

        $credit_card = null;
        if (isset($data['credit_card_number'])) {
            $credit_card = '*************' . substr($data['credit_card_number'], -4);
        }
        $payment->credit_card_number    = $credit_card;
        $payment->credit_card_month     = $data['credit_card_month'] ?? '';
        $payment->credit_card_year      = $data['credit_card_year'] ?? '';
        $payment->cheque_number         = $data['cheque_number'] ?? '';
        $payment->cheque_due_date       = $data['cheque_due_date'] ?? '';
        $payment->bank_transfer_account = $data['bank_transfer_account'] ?? '';
        $payment->card_type             = $data['card_type'] ?? '';


        if (is_array($data['brand_id'])) {
            $payment->brand_id = (int)$data['brand_id.id'];
        } else {
            $payment->brand_id = $data['brand_id'];
        }


        if (isset($data['payment_type']) && $data['payment_type']) {
            $payment->payment_type = $data['payment_type'];
        }
        $payment->foreigner             = $data['foreigner'] ?? 0;

        $payment->save();

        $payment->rentals()->sync($data['rental_id'] ?? []);

        $payment->documents()->sync($data['documents']);

        $payment->save();

        return new PaymentResource($payment);
    }

    public function create(Request $data, $lng)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $payment = $this->create_payment($data->all());
        $payment->addDocuments();//needed for merge for v1

        if ($data->rental_id) {
            $payment->rentals()->attach($data->rental_id);
            $payment->save();
        }

        if (!$payment) {
            return redirect()->back()->withInput();
        }

        if ($data->ajax()) {
            return response()->json($payment);
        }

        $new = true;
        if (!$payment->wasRecentlyCreated) {
            $new = false;
        }

        return redirect()->route('edit_payment_view', [
            'cat_id'=>$payment->id,
            'locale'=>$lng,
            'payment_type' => $payment->payment_type,
            'new' => $new
        ]);
    }

    public function getMethods(Request $request) {
        return PaymentMethodResource::collection(Payment::PAYMENTS_METHODS);
    }

    public function getCards(Request $request)
    {
        return PaymentCardsResource::collection(array_keys(Payment::CARD_TYPES));
    }

    public function update_store_api_modal($data) // from trait invoked
    {
        $validator = Validator::make($data, [
            'payment_datetime' => 'required|date|before:now',
            'payer_id' => 'nullable',
            'payer_type' => 'required',
            'amount' => 'required',
            'user_id' => 'required',
            // 'station' => 'required',
            'place' => 'nullable',
            // 'payment_method' => 'required',
            'comments' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $payment = Payment::firstOrNew(['id' => $data['id'] ?? null]);

        $payment->payment_datetime      = $data['payment_datetime'];
        $payment->user_id               = $data['user_id'];
        $payment->payer_id              = $data['payer_id'];
        $payment->payer_type            = ucwords($data['payer_type']);
        $payment->amount                = $data['amount'];
        $payment->station_id            = $data['station'] ?? $data['station_id'];
        $payment->balance               = $data['balance'] ?? null;
        $payment->method                = isset($data['payment_method']) ? $data['payment_method'] : $data['method'];
        $payment->comments              = $data['comments'];
        $payment->reference             = $data['reference'] ?? null;
        if(isset($data['place']['id'])){
        $payment->place_id              = $data['place']['id'] ;
        $payment->place_text            = $data['place']['name'];
        }else{
            $payment->place_id              =  null;
            $payment->place_text            =  null;
        }

        $credit_card = null;
        if (isset($data['credit_card_number'])) {
            $credit_card = '*************' . substr($data['credit_card_number'], -4);
        }
        $payment->credit_card_number    = $credit_card;
        $payment->credit_card_month     = $data['credit_card_month'] ?? '';
        $payment->credit_card_year      = $data['credit_card_year'] ?? '';
        $payment->cheque_number         = $data['cheque_number'] ?? '';
        $payment->cheque_due_date       = $data['cheque_due_date'] ?? '';
        $payment->bank_transfer_account = $data['bank_transfer_account'] ?? '';
        $payment->card_type             = $data['card_type'] ?? '';

        if (isset($data['brand_id'])) {
            $payment->brand_id = $data['brand_id'];
        }

        if (isset($data['payment_type']) && $data['payment_type']) {

            $payment->payment_type =  $data['payment_type'];
        }
        $payment->foreigner  = $data['foreigner'] ?? 0;

        $payment->save();

        $payment->rentals()->sync($data['rental_id'] ?? []);

        $payment->documents()->sync($data['documents']);

        $payment->save();

        return $payment;
    }


}
