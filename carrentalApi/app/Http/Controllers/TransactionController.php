<?php

namespace App\Http\Controllers;

use App\Http\Resources\Transactor;
use App\Http\Resources\TransactorCollection;
use App\Http\Resources\TransactorRental;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TransactionController extends Controller
{
    public function search_ajax(Request $data) {
        $transactions = Transaction::get();
    }

    public function search_transactor_from_rental_ajax(Request $data) {
        $rental = $data['rental'];
        $term = $data['search'];
        $transactions = Transaction::searchTransactor($term)->with('transactor');

        if ($rental) {
            $transactions = $transactions->where('rental_id', $rental);
        }
        if ($data['invoice']) {
            $transactions = $transactions->whereHas('invoice');
        }
        if ($data['debit']) {
            $transactions = $transactions->where('debit', $data['debit']);
        }

        // $transactions = $transactions->whereHas('rental', function($q) {
        //     $q->where('status', Rental::STATUS_ACTIVE)->orWhere('status', Rental::STATUS_PRE_CHECKED_IN);
        // });

        $transactions = $transactions->groupBy(['transactor_id', 'transactor_type'])->take(Cookie::get('pages') ?? 5);

        TransactorRental::withoutWrapping();
        return TransactorRental::collection($transactions->get());
    }

    public function search_transactor_ajax(Request $data)
    {
        $term = $data['search'];
        $transactions = Transaction::searchTransactor($term)->with('transactor');
        if ($data['rental_id']) {
            $transactions = $transactions->where('rental_id', $data['rental_id']);
        }
        if ($data['booking_id']) {
            $transactions = $transactions->where('booking_id', $data['booking_id']);
        }
        if ($data['invoice']) {
            $transactions = $transactions->whereHas('invoice');
        }

        $transactions = $transactions->groupBy(['transactor_id', 'transactor_type']);
        // if ($data['rental_id']) {
        //     $payments = Rental::find($data['rental_id'])->payments()->select('payer_id', 'payer_type', DB::raw('SUM(amount) as amount'))->groupBy(['payer_id', 'payer_type']);
        // }

        Transactor::withoutWrapping();
        return Transactor::collection($transactions->get());
    }

    public function search_transactor_ajax2(Request $request) {
        $term = $request['search'];
        $transactions = Transaction::searchTransactor($term)->with('transactor');
        if ($request['rental_id']) {
            $transactions = $transactions->where('rental_id', $request['rental_id']);
        }
        if ($request['booking_id']) {
            $transactions = $transactions->where('booking_id', $request['booking_id']);
        }
        if ($request['invoice']) {
            $transactions = $transactions->whereHas('invoice');
        }

        if($request['type'] && $request['id']){
            $transactions = $transactions->where('transactor_id', $request['id'])->where('transactor_type', 'App\\' .ucfirst($request['type']));
        }

        $transactions = $transactions->groupBy(['transactor_id', 'transactor_type']);
        // if ($data['rental_id']) {
        //     $payments = Rental::find($data['rental_id'])->payments()->select('payer_id', 'payer_type', DB::raw('SUM(amount) as amount'))->groupBy(['payer_id', 'payer_type']);
        // }

         return new TransactorCollection($transactions->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

}
