<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class PaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item) use ($request) {
            $normalized = (new PaymentResource($item))->toArray($request);
           // $normalized['user']=new UserResource($item->user);
           // number_format((float)Payment::query()->where('payment_type', 'payment')->sum('amount'),2);
            //  $normalized['total_payment'] = Payment::query()->where('payment_type','payment')->sum('amount');
            $normalized['total_payment'] = number_format((float) $this->collection->where('payment_type','payment')->sum('amount'), 2) ?? 0;
            $normalized['total_refund'] = number_format((float) $this->collection->where('payment_type', 'refund')->sum('amount'), 2)  ?? 0;
            $normalized['total_pre_auth'] = number_format((float) $this->collection->where('payment_type', 'pre-auth')->sum('amount'), 2)  ?? 0;
            $normalized['total_refund_pre_auth'] = number_format((float) $this->collection->where('payment_type', 'refund-pre-auth')->sum('amount'), 2)  ?? 0;

            $normalized['total_results_p'] = number_format($this->collection->where('payment_type', 'payment')->count('id'));
            $normalized['total_results_r'] = number_format($this->collection->where('payment_type', 'refund')->count('id'));
            $normalized['total_results_pa'] = number_format($this->collection->where('payment_type', 'pre-auth')->count('id'));
            $normalized['total_results_rpa'] = number_format($this->collection->where('payment_type', 'refund-pre-auth')->count('id'));

            $normalized['g_total_results_p'] = Payment::query()->where('payment_type', 'payment')->count('id');
            $normalized['g_total_payment'] = number_format((float) Payment::query()->where('payment_type', 'payment')->sum('amount'), 2)  ?? 0;

            $normalized['g_total_results_r'] = Payment::query()->where('payment_type', 'refund')->count('id');
            $normalized['g_total_refund'] = number_format((float) Payment::query()->where('payment_type', 'refund')->sum('amount'), 2)  ?? 0;

            $normalized['g_total_results_pa'] = Payment::query()->where('payment_type', 'pre-auth')->count('id');
            $normalized['g_total_pre_auth'] = number_format((float) Payment::query()->where('payment_type', 'pre-auth')->sum('amount'), 2)  ?? 0;

            $normalized['g_total_results_rpa'] = Payment::query()->where('payment_type', 'refund-pre-auth')->count('id');
            $normalized['g_total_refund_pre_auth'] = number_format((float) Payment::query()->where('payment_type', 'refund-pre-auth')->sum('amount'), 2)  ?? 0;
            return $normalized;
        });
    }
}
