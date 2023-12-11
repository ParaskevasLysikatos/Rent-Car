<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MydataController extends Controller
{
    public static function send(array $data) {
        $data['url'] = config('mydata.url');
        $data['aadeUserId'] = config('mydata.aadeUserId');
        $data['ocpApimSubscriptionKey'] = config('mydata.ocpApimSubscriptionKey');
        $data['vatNumber'] = config('mydata.vatNumber');
        $data['series'] = isset($data['series']) && $data['series'] ? $data['series'] : config('mydata.series');
        $data['company'] = config('mydata.company');;

        $validator = Validator::make($data, [
            'url' => 'required',
            'aadeUserId' => 'required',
            'ocpApimSubscriptionKey' => 'required',
            'vatNumber' => 'required',
            'series' => 'required',
            'branch' => 'required',
            'type' => 'required',
            'invoicee' => 'nullable',
            'invoicee.vatNumber' => 'nullable',
            'invoicee.street' => 'nullable',
            'invoicee.number' => 'nullable',
            'invoicee.postalCode' => 'nullable',
            'invoicee.city' => 'nullable',
            'issueDate' => 'required',
            'payments' => 'present',
            'netValue' => 'required',
            'vatAmount' => 'required',
            'company' => 'required',
            'invoice' => 'required'
        ]);

        if ($validator->fails()) {
            Log::debug(json_encode($validator->errors()->all()));
            return;
        }

        // dd($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('mydata.url'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = json_decode(curl_exec($ch));

        // $client = new Client();
        // $request = $client->post(config('mydata.url'), ['form_params' => $data]);
        // $response = $request->getBody();

        return $result && property_exists($result, 'response') && property_exists($result->response, 'statusCode') && $result->response->statusCode == 'Success';
    }
}
