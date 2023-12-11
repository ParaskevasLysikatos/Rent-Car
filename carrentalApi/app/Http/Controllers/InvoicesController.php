<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Company;
use App\Driver;
use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\InvoiceResource;
use App\Invoice;
use App\Payment;
use App\Station;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $request, $lng)
    {
        $term = $request['search'];
        $invoices = Invoice::query();

        if ($term) {
            $invoices = $invoices->where('sequence_number', 'like', "%" . $term . "%");
        }

        $invoices = $invoices->filter($request)->applyOrder($request);

        if ($request->has('export')) {
            $filename = 'vouchers';
            if ($request->type) {
                $filename = $request->type . 's';
            }
            if ($request->station_id) {
                $filename .= '-' . Station::find($request->station_id)->code;
            }
            if ($request->date) {
                if ($request->date['from']) {
                    $filename .= '-' . $request->date['from'];
                }
                if ($request->date['to']) {
                    $filename .= '-' . $request->date['to'];
                }
            }
            return ExportController::createFileFromCollection($invoices->get(), $request['export-field'] ?? [], $filename);
        }

        $total = (clone $invoices)->select(DB::raw('SUM(final_total) as final_total'))->first()->final_total;

        $invoices = $invoices->paginate(Cookie::get('pages') ?? '5');
        return view('invoices.preview', compact(['invoices', 'total', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $term = $request['search'];
        $invoices = Invoice::query()->orderBy('created_at', 'desc');

        if ($term) {
            $invoices = $invoices->where('sequence_number', 'like', "%" . $term . "%");
        }

        $invoices = $invoices->filter($request)->applyOrder($request);

        $total = (clone $invoices)->select(DB::raw('SUM(final_total) as final_total'))->first()->final_total;

            return new InvoiceCollection($invoices->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            $invoice = Invoice::find($data['cat_id']);
            $printings = [$invoice->last_printing];

            return view('invoices.create', [
                'invoice'           => $invoice,
                'lng'               => $lng,
                'new'               => $data->new ? $data->new : false,
                'printings'         => $printings
            ]);
        }
        //        return view('invoices.create', ['lng'=>$lng, 'exist_invoices'=>$exist_invoices]);
        return view('invoices.create', compact(['lng', 'lastNumber', 'minDate']));

    }

    public function delete_api(Request $data)
    {

            $invoices = Invoice::find($data['id']);// v2 sends one by one
            $invoices->delete();
            return new InvoiceResource($invoices);
    }


    public function delete(Request $data)
    {
        $invoices = Invoice::whereIn('id', $data['ids'])->get();
        foreach ($invoices as $invoice) {
            $invoice->delete();
        }
        return "ok";
    }

    public function edit(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        return new InvoiceResource($invoice);
    }

    public function create_invoice($data)
    {
        $invoice = new Invoice();
        $invoice->invoicee_id           = $data['invoicee_id'];
        $invoice->invoicee_type         = $data['invoicee_type'];
        $invoice->type                  = $data['invoicee_type'] == Driver::class ? Invoice::RECEIPT : Invoice::INVOICE;
        $invoice->date                  = $data['date'];
        // $invoice->range         = $data['range'];
        // $invoice->discount      = $data['discount'];
        // $invoice->balance               = $data['balance']
        $invoice->sub_discount_total    = $data['sub_discount_total'];
        $invoice->discount              = $data['discount'];
        // $invoice->fpa                   = $data['fpa'];
        $invoice->fpa_perc              = $data['fpa_perc'];
        $invoice->final_fpa             = $data['final_fpa'];
        $invoice->total                 = $data['total'];
        $invoice->final_total           = $data['final_total'];
        $invoice->notes                 = $data['notes'];
        $invoice->brand_id              = $data['brand_id'];
        $invoice->station_id            = $data['station_id'];
        $invoice->rental_id             = $data['rental_id'];
        $invoice->save();

        return $invoice;
    }

    public function create_invoice_api(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'invoicee.id'           => 'required',
            'invoicee.type'         => 'required',
            'date'                  => 'required|date',
            'discount'              => 'nullable|numeric',
            'notes'                 => 'nullable',
           // 'print'                 => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $invoicee_type = $data->input('invoicee.type');
        $invoicee_type = $invoicee_type == 'agent' ? Agent::class : ($invoicee_type == 'driver' ? Driver::class : Company::class);
        $invoice = new Invoice();
        $invoice->invoicee_id           = $data->input('invoicee.id');
        $invoice->invoicee_type         = $invoicee_type;
        $invoice->type                  = $invoicee_type == Driver::class ? Invoice::RECEIPT : Invoice::INVOICE;
        $invoice->date                  = $data['date'];
        // $invoice->range         = $data['range'];
        // $invoice->discount      = $data['discount'];
        // $invoice->balance               = $data['balance']
        $invoice->sub_discount_total    = $data['sub_discount_total'];
        $invoice->discount              = $data['discount'];
        // $invoice->fpa                   = $data['fpa'];
        $invoice->fpa_perc              = $data['fpa_perc'];
        $invoice->final_fpa             = $data['final_fpa'];
        $invoice->total                 = $data['total'];
        $invoice->final_total           = $data['final_total'];
        $invoice->notes                 = $data['notes'];

        if (is_array($data['brand_id'])) {
            $invoice->brand_id              = (int)$data['brand_id.id'];
        } else {
            $invoice->brand_id              = $data['brand_id'];
        }


        if (is_array($data['station_id'])) {
            $invoice->station_id            = (int)$data['station_id.id'];
        } else {
            $invoice->station_id            = $data['station_id'];
        }

        if (is_array($data['rental_id'])) {
            $invoice->rental_id             = (int)$data['rental_id.id'];
        } else {
            $invoice->rental_id             = $data['rental_id'];
        }


        $invoice->save();

        return $invoice;
    }

    public function create(Request $data, $lng)
    {
        $validator = Validator::make($data->all(), [
            'invoicee_id'           => 'required',
            'invoicee_type'         => 'required',
            'date'                  => 'required|date',
            'range'                 => 'nullable',
            'discount'              => 'nullable|numeric',
            'fpa'                   => 'nullable|numeric',
            'notes'                 => 'nullable',
            'print'                 => 'nullable',
        ]);
        if ($validator->fails()) {
            return  $validator->errors()->first();
        }

        $invoice = $this->create_invoice($data);
        $invoice->addPaymentsFromRequest();//needed for merge for v1

        if ($data->has('products')) {
            foreach ($data['products'] as $product => $val) {
                $invoice->addItem($val['code'], $val['title'], $val['price'], $val['total'], $val['quantity']);
            }
        }

        if ($data->has('payment_id') && $data->payment_id) {
            foreach ($data->payment_id as $payment_id) {
                $payment = Payment::find($payment_id);
                if ($invoice->rental_id && !$payment->rental()) {
                    $payment->rentals()->attach($invoice->rental_id);
                    $payment->save();
                }
                $invoice->payments()->attach($payment_id);
            }
        }

        $invoice->load('payments');
        $invoice->load('collections');
        $invoice->load('items');
        $invoice->addPrinting();
        $invoice->sendToAade();

        $rental = $invoice->rental;
        $rental->addExtaCharges($invoice->final_total);
        $rental->save();

        $transaction = new Transaction();
        if ($data['rental_id']) {
            $transaction->rental_id = $data['rental_id'];
        }
        $transaction->transactor_id = $data['invoicee_id'];
        $transaction->transactor_type = $data['invoicee_type'];
        $transaction->debit = $data['final_total'];
        $transaction->invoice_id = $invoice->id;
        $transaction->save();

        $print = ($data['print']) ? 'on' : 'off';

        $new = true;
        if (!$invoice->wasRecentlyCreated) {
            $new = false;
        }

        return redirect()->route('edit_invoice_view', ['cat_id' => $invoice->id, 'locale' => $lng, 'new' => $new]);
    }

    public function update(Request $request, $lng)
    {
        $payment_ids = $request->has('payment_id') && $request->payment_id ? $request->payment_id : [];
        $invoice = Invoice::find($request->cat_id);
        $invoice->payments()->detach($invoice->payments()->wherePivotNotIn('payment_id', $payment_ids)->get(['payments.id'])->pluck('id'));
        foreach ($payment_ids as $payment_id) {
            if (!$invoice->payments()->wherePivot('payment_id', $payment_id)->exists()) {
                $invoice->payments()->attach($payment_id);
            }
        }
        $invoice->addPaymentsFromRequest();
        $invoice->addPrinting();

        return redirect()->route('edit_invoice_view', ['cat_id' => $invoice->id, 'locale' => $lng]);
    }

    public function update_api(Request $request, $lng = null)
    {
        $invoice = Invoice::find($request->id);
        $invoice->payments()->sync($request->payment_id);
        $invoice->notes = $request['notes'];
        $invoice->save();
        $invoice->addPrinting();

        return new InvoiceResource($invoice);
    }

    public function create_api(Request $data, $lng = null)
    {
      $invoice = $this->create_invoice_api($data);
        if ($data->has('rows')) {
            foreach ($data['rows'] as $product => $val) {
                $invoice->addItem($val['code'], $val['title'], $val['price'], $val['total'], $val['quantity']);
            }
        }

        if ($data->has('payment_id') && $data->payment_id) {
            foreach ($data->payment_id as $payment_id) {
                $payment = Payment::find($payment_id);
                if ($invoice->rental_id && !$payment->rental()) {
                    $payment->rentals()->attach($invoice->rental_id);
                    $payment->save();
                }
                $invoice->payments()->attach($payment_id);
            }
        }

        $invoice->load('payments');
        $invoice->load('collections');
        $invoice->load('items');
        $invoice->addPrinting();
        $invoice->sendToAade();

        $rental = $invoice->rental;
        $rental->addExtaCharges($invoice->final_total);
        $rental->save();

        $transaction = new Transaction();
        if ($data['rental_id']) {
            $transaction->rental_id = $data['rental_id'];
        }
        $transaction->transactor_id = $data->input('invoicee_id.id');
        $transaction->transactor_type = $data->input('invoicee_id.type') == 'agent' ? Agent::class : ($data->input('invoicee_id.type') == 'driver' ? Driver::class : Company::class);;
        $transaction->debit = $data['final_total'];
        $transaction->invoice_id = $invoice->id;
        $transaction->save();

        $print = ($data['print']) ? 'on' : 'off';

        $new = true;
        if (!$invoice->wasRecentlyCreated) {
            $new = false;
        }

        return new InvoiceResource($invoice);
    }



    public function check_range(Request $data)
    {
        $invoice = Invoice::first();

        if ($invoice === null) {
            return null;
        }

        return $invoice;
    }

    public function single_invoice($lng, $invoice)
    {
        $invoice = Invoice::find($invoice);
        return view('invoices.single-invoice', [
            'invoice' => $invoice,
            'lng' => $lng
        ]);
    }


    public function badPrinting(Request $request){
        $sequence_number=$request['sequence_number'];
        /* Existing File name */
        $file1 = public_path('storage/cars/car-/invoice/' .$sequence_number . '.pdf');
       // $file_path = '/cars/car-/invoice'.$sequence_number.'.pdf';

        /* New File name */
       // $new_file_name = '/cars/car-/invoice'. $sequence_number.'-OLD.pdf';
        $file2 = public_path('storage/cars/car-/invoice/' . $sequence_number . '-OLD.pdf');

        /* Rename Your New File name */
        rename($file1, $file2);

        $document_id=DB::table('document_links')->where('document_link_id', $request['id'])->first();

        DB::table('document_links')->where('document_link_id', $request['id'])->delete();

        sleep(2);
        DB::table('documents')->where('id', $document_id->document_id)->delete();

         $invoice=Invoice::find($request['id']);

       // $invoice->save();

        return new InvoiceResource($invoice);
    }

}