<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyPreferencesResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'job' => $this->job,
            'afm' => $this->afm,
            'doi' => $this->doi,
            'title' => $this->title,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'mite_number' => $this->mite_number,
            'station_id' => new StationResource($this->station) ?? $this->station_id,
            //'place' => new PlaceResource($this->place),
            'place' => ['id' => $this->place_id, 'name' => $this->place->profiles[0]->title],
            'quote_source' => new SourceResource($this->quote_source),
            'quote_source_id' => new SourceResource($this->quote_source) ?? $this->quote_source_id,
            'booking_source' => new SourceResource($this->booking_source),
            'booking_source_id' =>  new SourceResource($this->booking_source) ?? $this-> booking_source_id,
            'rental_source' => new SourceResource($this->rental_source),
            'rental_source_id' => new SourceResource($this->rental_source) ?? $this->rental_source_id,
            'checkin_free_minutes' => $this->checkin_free_minutes,
            'vat' => $this->vat,
            'timezone' => $this->timezone,
            'quote_prefix' => $this->quote_prefix,
            'booking_prefix' => $this->booking_prefix,
            'rental_prefix' => $this->rental_prefix,
            'invoice_prefix' => $this->invoice_prefix,
            'receipt_prefix' => $this->receipt_prefix,
            'payment_prefix' => $this->payment_prefix,
            'pre_auth_prefix' => $this->pre_auth_prefix,
            'refund_prefix' => $this->refund_prefix,
            'refund_pre_auth_prefix' => $this->refund_pre_auth_prefix,
            'quote_available_days' => $this->quote_available_days,
            'show_rental_charges' => $this->show_rental_charges,
            'rental_rate_terms' => $this->rental_rate_terms,
            'rental_vehicle_condition' => $this->rental_vehicle_condition,
            'rental_gdpr' => $this->rental_gdpr,
            'invoice_first_box' => $this->invoice_first_box,
            'invoice_second_box' => $this->invoice_second_box
        ];
    }
}