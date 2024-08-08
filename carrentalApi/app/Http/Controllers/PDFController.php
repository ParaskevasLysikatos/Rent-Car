<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Quote;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PDFController extends Controller
{
    public function create_pdf($model, $view, $lng = 'el', $args = []): PDF {
        $pdf = App::make('dompdf.wrapper');

        $args = array_merge([
            'model'=>$model,
            'lng' =>$lng
        ], $args);

        $pdf->loadHTML( view('print.'.$view, $args) );

        return $pdf;
    }

    public function stream_pdf(PDF $pdf, $model) {
        $name = $model->sequence_number;
        if ($model->modification_number) {
            $name .= '-'.$model->modification_number;
        }
        $name .= '.pdf';

        return $pdf->stream($name);
    }

    public function create_payment_api(Request $data,$id, $lng=null)
    {
        $model = Payment::find($id);

        if ($model->payment_type == Payment::PAYMENT_TYPE || $model->payment_type == Payment::PRE_AUTH_TYPE) {
            $pdf = $this->create_pdf($model, 'payment', $lng);
        } else {
            $pdf = $this->create_pdf($model, 'refund', $lng);
        }

        // return $this->stream_pdf($pdf, $model);
        return $model->getLastPrintingAttribute();
      //  return new PaymentResource($model);
    }


    public function create_payment(Request $data, $lng){
        $model = Payment::find($data['id']);

        if ($model->payment_type == Payment::PAYMENT_TYPE || $model->payment_type == Payment::PRE_AUTH_TYPE) {
            $pdf = $this->create_pdf($model, 'payment', $lng);
        } else {
            $pdf = $this->create_pdf($model, 'refund', $lng);
        }

        return $this->stream_pdf($pdf, $model);
    }

    public function create_invoice(Request $data, $lng){
        $model = Invoice::find($data['id']);

        if ($model->type == Invoice::INVOICE) {
            $pdf = $this->create_pdf($model, 'invoice', $lng);
        } else {
            $pdf = $this->create_pdf($model, 'receipt', $lng);
        }

        return $this->stream_pdf($pdf, $model);
    }


    public function create_invoice_api(Request $data,$id, $lng=null)
    {
        $model = Invoice::find($id);

        if ($model->type == Invoice::INVOICE) {
            $pdf = $this->create_pdf($model, 'invoice', $lng);
        } else {
            $pdf = $this->create_pdf($model, 'receipt', $lng);
        }

        // return $this->stream_pdf($pdf, $model);
        return $model->getLastPrintingAttribute();
       // return new InvoiceResource($model);
    }


    public function create_quote(Request $data, $lng){
        $model = Quote::find($data['id']);

        $pdf = $this->create_pdf($model, 'quote-agreement', $lng);

        return $this->stream_pdf($pdf, $model);
    }

    public function create_quote_api(Request $data,$id,$lng=null)
    {
        $model = Quote::find($id);

        $pdf = $this->create_pdf($model, 'quote-agreement', $lng);

        //  return $this->stream_pdf($pdf, $model);
        return $model->getLastPrintingAttribute();
       // return new QuoteResource($model);
    }

    public function create_booking(Request $data, $lng){
        $model = Booking::find($data['id']);

        $pdf = $this->create_pdf($model, 'booking-agreement', $lng);

        return $this->stream_pdf($pdf, $model);
    }

    public function create_booking_api(Request $data,$id ,$lng=null)
    {
        $model = Booking::find($id);

        $pdf = $this->create_pdf($model, 'booking-agreement', $lng);

        // return $this->stream_pdf($pdf, $model);
         return $model->getLastPrintingAttribute();
        //return new BookingResource($model);
    }

    public function create_rental_api(Request $data, $id, $lng=null)
    {
        $model = Rental::find($id);

        $pdf = $this->create_pdf($model, 'rental-agreement', $lng);

        // return $this->stream_pdf($pdf, $model);
        return $model->getLastPrintingAttribute();
      // return new RentalResource($model);
    }

    public function create_rental(Request $data, $lng){
        $model = Rental::find($data['id']);

        $pdf = $this->create_pdf($model, 'rental-agreement', $lng);

        return $this->stream_pdf($pdf, $model);
    }

    public function getSinglePrinter(Request $request) {
        return view('template-parts.single_printer', ['pdf_src' => asset('storage/'.$request->pdf_src)]);
    }

    private function mail_validator(Request $data) {
        $validator = Validator::make($data->all(), [
            'mail_to' => 'required'
        ]);
        return $validator;
    }

    private function mail(Request $data, $model, $folder, $view) { //works for both v1 and v2
        $validator = $this->mail_validator($data);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML( view($view, [
            'model' => $model,
            'lng' => Lang::locale()
        ]) );

        $content = $pdf->download()->getOriginalContent();
        Storage::put('public/mailer/pdf/'.$folder.'/pdf-'.$data['id'].'-'.date('Y-m-d').'.pdf',$content) ;

        if(is_array($data['mail_to'])){// multiple senders
            foreach($data['mail_to'] as $itemEmail){
                Mail::send([], [], function ($message) use ($data, $folder,$itemEmail,$view,$model) {
                    $message->to($itemEmail, config('app.name'))->subject($data['mail_subject']);
                    $message->from('rental@carrentalthessaloniki.com', 'Blue rent a car');
                    $message->setBody($data['mail_notes'], 'text/html');
                    //  $message->attach('storage/mailer/pdf/' . $folder . '/pdf-' . $data['id'] . '-' . date('Y-m-d') . '.pdf');
                    //['notes' => str_replace('<br>', '', $data['mail_notes'])]
                    $message->attach('storage/'.$data['pdf_src']);
                    if(str_contains($data['pdf_src'], 'RNT')){
                        foreach ($model->documents as $doc) {
                            $message->attach('storage/'.$doc->path);
                        }
                        $message->attach('storage/GDPR-ΠΟΛΙΤΙΚΗ ΑΠΟΡΡΗΤΟΥ.docx');
                        $message->attach('storage/ΟΡΟΙ-ΜΙΣΘΩΤΗΡΙΟΥ.docx');
                    }
                });
            }
        }
        // else{//normal one sender
        // Mail::send([],[], function($message) use ($data, $folder, $view, $model) {
        //     $message->to($data['mail_to'], config('app.name'))->subject($data['mail_subject']);
        //     $message->from('rental@carrentalthessaloniki.com', 'Blue rent a car111');
        //     $message->setBody($data['mail_notes'], 'text/html');
        //         // $message->attach('storage/mailer/pdf/'.$folder.'/pdf-'.$data['id'].'-'.date('Y-m-d').'.pdf');
        //         $message->attach('storage/'.$data['pdf_src']);
        //         if (str_contains($data['pdf_src'], 'RNT')) {
        //             foreach ($model->documents as $doc) {
        //                 $message->attach('storage/' . $doc->path);
        //             }
        //             $message->attach('storage/GDPR-ΠΟΛΙΤΙΚΗ ΑΠΟΡΡΗΤΟΥ.docx');
        //             $message->attach('storage/ΟΡΟΙ-ΜΙΣΘΩΤΗΡΙΟΥ.docx');
        //         }
        // });
        // }

        if($data->wantsJson()){
          return  response()->json('Η αποστολή ολοκληρώθηκε', 200);
        }

        return __('Η αποστολή ολοκληρώθηκε');
    }

    public function mail_payment(Request $data, $lng=null){
        $payment = Payment::find($data['id']);
        return $this->mail($data, $payment, 'payments', 'print.payment');
    }

    public function mail_invoice(Request $data, $lng=null){
        $invoice = Invoice::find($data['id']);
        return $this->mail($data, $invoice, 'invoices', 'print.invoice');
    }

    public function mail_quote(Request $data, $lng=null){
        $quote = Quote::find($data['id']);
        return $this->mail($data, $quote, 'quotes', 'print.quote-agreement');
    }

    public function mail_booking(Request $data, $lng=null){
        $booking = Booking::find($data['id']);
        return $this->mail($data, $booking, 'bookings', 'print.booking-agreement');
    }

    public function mail_rental(Request $data, $lng=null){
        $rental = Rental::find($data['id']);
        return $this->mail($data, $rental, 'rentals', 'print.rental-agreement');
    }

   public function live_export_pdf(Request $data){
       return "This is pdf";
   }

}
