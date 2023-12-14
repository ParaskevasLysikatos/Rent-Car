<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RentalResource extends JsonResource
{
    public static $wrap = null;
    private $exclude_fields = [];

    public function exclude_fields($fields) {
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
            'documents' => DocumentResource::collection($this->documents),
            'tags'=>new TagCollection($this->tags),
            'vehicle' => new VehicleResource($this->vehicle),
            'station_checkin'=>new StationResource($this->checkin_station),
            'station_checkout' => new StationResource($this->checkout_station),
            'customer'=> $this->customer,
            'driver' => new DriverResource($this->customer),
            'booking_source'=>new SourceResource($this->booking_source), //Συνεργάτης
            'program'=>new ProgramResource($this->program) ?? '',

            'agent' => new AgentResource($this->agent), //Συνεργάτης-Πηγή
            'type' => new TypeResource($this->type),

            'user_id'=> $this->user_id,
            'contact_information_id'=> $this->contact_information_id,
            'program_id'=> $this->program_id,
            'status'=> $this->status,
            'duration'=> $this->duration,
           // 'rate'=> $this->rate,
            'extension_rate' =>$this->extension_rate,
            'may_extend' =>$this->may_extend,
           // 'checkout_station_fee'=>$this->checkout_station_fee,
           // 'checkin_station_fee' => $this->checkin_station_fee,
            'checkin_place' => ['id' => $this->checkin_place_id, 'name' => $this->checkin_place_text],
            'checkout_km'=> $this->checkout_km,
            'checkout_fuel_level'=> $this->checkout_fuel_level,
            'checkin_km'=> $this->checkin_km,
            'checkin_fuel_level'=> $this->checkin_fuel_level,
            'comments'=> $this->comments,
            'excess' =>$this->excess,
            'sub_account_type' => $this->sub_account_type,
            'sub_account_id'=>$this->sub_account_id,
            'checkout_driver_id'=>$this->checkout_driver_id,
            'checkin_driver_id'=>$this->checkin_driver_id,
            'extra_day'=> $this->extra_day,
            'checkin_duration'=>$this->checkin_duration,
            'modification_number'=> $this->modification_number,
            'booking_id'=> $this->booking_id,
            'booked_checkin_datetime' =>$this->booked_checkin_datetime,
            //'vat_included'=> $this->vat_included,
            'cancel_reason_id'=> $this->cancel_reason_id,
            'extra_charges'=> $this->extra_charges,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sequence_number' => $this->sequence_number,
            'source_id' => $this->source_id,
            'brand_id' => $this->brand_id,
            'company_id' => $this->company_id,
            'driver_id' => $this->driver_id,
            'phone' => $this->driver->phone ?? null,
            'drivers' => $this->drivers->pluck('id'),
            'agent_id' => $this->agent_id,
            'sub_account' => $this->sub_account ? new SubAccountResource($this->sub_account): null,
            'confirmation_number' => $this->confirmation_number,
            'agent_voucher' => $this->agent_voucher,
            'checkout_datetime' => $this->checkout_datetime,
            'checkout_station_id' => $this->checkout_station_id,
            'checkout_place' => ['id' => $this->checkout_place_id, 'name' => $this->checkout_place_text],
            'checkout_comments' => $this->checkout_comments,
            'flight' => $this->flight,
            'checkin_datetime' => $this->checkin_datetime,
            'checkin_station_id' => $this->checkin_station_id,
           // 'checkin_place_id' => $this->checkin_place_id,
            'checkin_comments' => $this->checkin_comments,
            'type_id' => $this->type_id,
            'vehicle_id' => $this->vehicle_id,

          //  'manual_agreement'=> $this->manual_agreement,
         //   'charge_type_id' => $this->charge_type_id,
         //   'distance'=> $this->distance,
          //  'distance_rate'=> $this->distance_rate,
          //  'rental_fee'=> $this->rental_fee,
          //  'transport_fee'=> $this->transport_fee,
          //  'insurance_fee'=> $this->insurance_fee,
         //   'options_fee'=> $this->options_fee,
         //   'fuel_fee'=> $this->fuel_fee,
          //  'subcharges_fee'=> $this->subcharges_fee,
          //  'discount'=> $this->discount,
          //  'total_net'=> $this->total_net,
          //  'vat'=> $this->vat,
        //    'vat_fee'=> $this->vat_fee,
           // 'total'=> $this->total,
           // 'voucher'=> $this->voucher,
            //'total_paid'=> $this->total_paid,
          //  'balance'=> $this->balance,


           // 'print'=> $this->getLastPrintingAttribute(),

           // 'transactions'=>TransactorRental::collection($this->transactions),

            'transitions'=>TransitionResource::collection($this->transitions),

            'exchanges'=>VehicleExchangeResource::collection($this->exchanges),

            'getKmDrivenAttribute'=>$this->getKmDrivenAttribute(),


            'IamRental'=>'',
            'summary_charges' => [
                'manual_agreement' => $this->manual_agreement,
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
         //   $array['items'] = BookingItemResource::collection($this->options);
            $array['payments'] = new PaymentCollection($this->payments);
            $array['invoices'] = new InvoiceCollection($this->invoices);
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
