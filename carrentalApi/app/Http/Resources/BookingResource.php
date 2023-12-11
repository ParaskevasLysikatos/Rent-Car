<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public static $wrap = null;
    private $exclude_fields = [];

    public function exclude_fields($fields)
    {
        if (!is_array($fields)) {
            $fields = [$fields];
        }
        $this->exclude_fields = $fields;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'drivers'=>$this->drivers,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'duration' => $this->duration,
            'flight' => $this->flight,
            'agent_voucher' =>$this->agent_voucher,
            'confirmation_number'=> $this->confirmation_number,
            'modification_number'=> $this->modification_number,
            'sequence_number'=> $this->sequence_number,
            //'discount_percentage' => $this->discount_percentage,

            //'driver_text'=> DriverResource::collection($this->customer) ?? null,
            'user_id' => $this->user_id,
            'company_id' => $this->company_id,
            'type_id' => $this->type_id,
            'brand_id' => $this->brand_id,
            'agent_id' => $this->agent_id,
            'source_id' => $this->source_id,
            'program_id' => $this->program_id,
            'vehicle_id'=> $this->vehicle_id,
            'quote_id'=> $this->quote_id,
         //   'rate'=> $this->rate,
            'contact_information_id'=> $this->contact_information_id,
            'cancel_reason_id'=> $this->cancel_reason_id,
         //   'charge_type_id' => $this->charge_type_id,

            'customer_id'=> $this->customer_id, //customer is the driver
            'customer_text' => $this->customer_text,
            'customer_type'=> $this->customer_type,
            'customer' => $this->customer,
            'customer_driver' => ['id' => $this->customer_id, 'name' => $this->customer_text],

            'checkout_datetime' => $this->checkout_datetime,
            'checkout_station_id' => $this->checkout_station_id,
            'checkout_place_id' => $this->checkout_place_id,
            'checkout_place_text'=> $this->checkout_place_text,
            //'checkout_station_fee' => $this->checkout_station_fee,
            'checkout_comments' => $this->checkout_comments,

            'checkin_datetime' => $this->checkin_datetime,
            'checkin_station_id' => $this->checkin_station_id,
            'checkin_place_id' => $this->checkin_place_id,
            'checkin_place_text'=> $this->checkin_place_text,
            //'checkin_station_fee' => $this->checkin_station_fee,
            'checkin_comments' => $this->checkin_comments,

            'may_extend' => $this->may_extend,
            //'estimated_km' => $this->estimated_km,
            //'valid_date' => $this->valid_date,
         //   'distance' => $this->distance,
         //   'distance_rate' => $this->distance_rate,
            //'transport_rate' => $this->transport_rate,

         //   'insurance_fee' => $this->insurance_fee,
         //   'options_fee' => $this->options_fee,
         //   'fuel_fee' => $this->fuel_fee,
         //   'subcharges_fee' => $this->subcharges_fee,
         //   'vat_fee' => $this->vat_fee,
         //   'transport_fee' => $this->transport_fee,
          //  'rental_fee' => $this->rental_fee,

         //   'discount' => $this->discount,
         //   'voucher' => $this->voucher,
         //   'total' => $this->total,
          //  'total_net' => $this->total_net,
          //  'total_paid' => $this->total_paid,
           // 'vat' => $this->vat,
           // 'balance' => $this->balance,
            'comments' => $this->comments,
           // 'vat_included'=> $this->vat_included,

           'sub_account' => $this->sub_account ? new SubAccountResource($this->sub_account): null,
           'sub_account_type'=> $this->sub_account_type,
            'sub_account_id'=> $this->sub_account_id,

           'phone'=> $this->phone,
           'extra_day'=> $this->extra_day,

            'print' => $this->getLastPrintingAttribute(),

           'extension_rate'=> $this->extension_rate,
           'excess'=> $this->excess,

           'type'=>new TypeResource($this->type),
           'station_checkin'=>new StationResource($this->checkin_station),
           'station_checkout'=>new StationResource($this->checkout_station),

        'checkout_place' => ['id' => $this->checkout_place_id, 'name' => $this->checkout_place_text],
        'checkin_place' => ['id' => $this->checkin_place_id, 'name' => $this->checkin_place_text],
        'booking_source' => new SourceResource($this->booking_source), //Πηγή
        'agent'=> new AgentResource($this->agent), //Συνεργάτης
       // 'company' => new CompanyResource($this->company),
        //'driver' => new DriverResource($this->customer) ?? null,
        'vehicle'=> new VehicleResource($this->vehicle),
        'documents' => DocumentResource::collection($this->documents),
        'tags' => new TagCollection($this->tags),
        'rental'=>new RentalResource($this->rental),
        'IamBooking'=>'',
      //  'optionsCheck'=>$this->options,
      'summary_charges'=>[
                'rate' => $this->rate,
                'charge_type_id' => $this->charge_type_id,
                'distance' => $this->distance,
                'distance_rate' => $this->distance_rate,

                'rental_fee' => $this->rental_fee,
                'insurance_fee' => $this->insurance_fee,
                'options_fee' => $this->options_fee,
                'fuel_fee' => $this->fuel_fee,
                'subcharges_fee' => $this->subcharges_fee,
                'vat_fee' => $this->vat_fee,
                'transport_fee' => $this->transport_fee,

                'discount' => $this->discount,
                'discount_fee' => ($this->subcharges_fee *  $this->discount) / 100,
                'vat_included' => $this->vat_included,
                'vat' => $this->vat,
                'voucher' => $this->voucher,

                'total' => $this->total,
                'total_net' => $this->total_net,
                'total_paid' => $this->total_paid,
                'balance' => $this->balance,

           'items' => BookingItemResource::collection($this->options),
      ],
        ];

        if (!in_array('items', $this->exclude_fields)) {
          //  $array['items'] = BookingItemResource::collection($this->options);
            $array['payments'] = new PaymentCollection($this->payments);
        }
        return $array;
    }

     // this.selectorSrv.searchDriver.next(null);//ok
    // this.selectorSrv.searchStation.next(null);--no need
    // this.selectorSrv.searchSource.next(null);//ok
    // this.selectorSrv.searchAgent.next(null);//ok
    // this.selectorSrv.searchSubAccount.next(null);//ok
    // this.selectorSrv.searchGroup.next(null);//ok
    // this.selectorSrv.searchVehicle.next(null);//ok
    // this.selectorSrv.searchCompany.next(null);-no need
}
