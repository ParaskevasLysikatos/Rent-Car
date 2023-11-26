<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Cookie;
use DB;
use App\Rental;

class SignatureController extends Controller
{
    public function uploadSignatureExcess(Request $data)
    {
        $data_uri = $data['file'];
        $rental=Rental::find($data['rental.id']);
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("../signatures/signatureExcess-".$rental->sequence_number.".png", $decoded_image);

      $rental->save();//renew pdf

        return $rental;
    }

    public function SignatureSee1(Request $data)
    {
        $rental = Rental::find($data['rental.id']);
       // file_put_contents("../signatures/signatureExcess-" . $rental->sequence_number . ".png", $decoded_image);

       $send='data:image/jpeg;base64,'.base64_encode(file_get_contents("../signatures/signatureExcess-" . $rental->sequence_number . ".png"));
        return response()->json($send, 200);
    }

    public function deleteSignatureExcess(Request $data)
    {
        $rental = Rental::find($data['rental.id']);
        unlink("../signatures/signatureExcess-" . $rental->sequence_number . ".png");

        $rental->save();// renew pdf

        return $rental;
    }

    public function uploadSignatureMain(Request $data)
    {
        $data_uri = $data['file'];
        $rental = Rental::find($data['rental.id']);
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("../signatures/signatureMain-" . $rental->sequence_number . ".png", $decoded_image);

        $rental->save(); //renew pdf

        return $rental;
    }

    public function SignatureSee2(Request $data)
    {
        $rental = Rental::find($data['rental.id']);
        // file_put_contents("../signatures/signatureExcess-" . $rental->sequence_number . ".png", $decoded_image);

        // return 'data:image/jpeg;base64,' . base64_encode(file_get_contents("../signatures/signatureMain-" . $rental->sequence_number . ".png"));
        $send = 'data:image/jpeg;base64,' . base64_encode(file_get_contents("../signatures/signatureMain-" . $rental->sequence_number . ".png"));
        return response()->json($send, 200);
    }

    public function deleteSignatureMain(Request $data)
    {
        $rental = Rental::find($data['rental.id']);
        unlink("../signatures/signatureMain-" . $rental->sequence_number . ".png");

        $rental->save(); // renew pdf

        return $rental;
    }

    public function uploadSignatureSecDriver(Request $data)
    {
        $data_uri = $data['file'];
        $rental = Rental::find($data['rental.id']);
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        file_put_contents("../signatures/signatureSecDriver-" . $rental->sequence_number . ".png", $decoded_image);

        $rental->save(); //renew pdf

        return $rental;
    }

    public function SignatureSee3(Request $data)
    {
        $rental = Rental::find($data['rental.id']);
        // file_put_contents("../signatures/signatureExcess-" . $rental->sequence_number . ".png", $decoded_image);

        // return 'data:image/jpeg;base64,' . base64_encode(file_get_contents("../signatures/signatureSecDriver-" . $rental->sequence_number . ".png"));
        $send = 'data:image/jpeg;base64,' . base64_encode(file_get_contents("../signatures/signatureSecDriver-" . $rental->sequence_number . ".png"));
        return response()->json($send, 200);
    }

    public function deleteSignatureSecDriver(Request $data)
    {
        $rental = Rental::find($data['rental.id']);
        unlink("../signatures/signatureSecDriver-" . $rental->sequence_number . ".png");

        $rental->save(); // renew pdf

        return $rental;
    }

}