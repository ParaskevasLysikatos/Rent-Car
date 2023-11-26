<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
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
        $array= [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'flight'=> $this->flight,
            //'agent_voucher' => $this->agent_voucher,
            //'confirmation_number' => $this->confirmation_number,
            'excess' => $this->excess,
            'phone'=> $this->phone,
            'modification_number' =>$this->modification_number,
            'sequence_number' =>$this->sequence_number,

            'sub_account_type' =>$this->sub_account_type,
            'sub_account_id' =>$this->sub_account_id,
            'sub_account' => $this->sub_account ? new SubAccountResource($this->sub_account) : null,

            //'driver_text'=> DriverResource::collection($this->driver_text),
            //'profiles' => ProfileResource::collection($this->profiles) ?? null,
            'user_id' =>  new UserResource($this->user) ?? $this->user_id,
            'company_id' => new CompanyResource($this->company) ??  $this->company_id,
            'type_id' =>  new TypeResource($this->type) ??  $this->type_id,
            'brand_id' =>  new BrandResource($this->brand) ?? $this->brand_id,
            'agent_id' =>  new AgentResource($this->agent) ?? $this->agent_id,
            'source_id' =>  new SourceResource($this->source) ?? $this->source_id,
            //'program_id' => $this->program_id,
            'charge_type_id' => $this->charge_type_id,
            'cancel_reason_id' =>$this->cancel_reason_id,

            'customer_id'=> $this->customer_id, // is the driver
            'customer_text' => $this->customer_text,
            'customer' => $this->customer,
            'customer_driver' => ['id' => $this->customer_id, 'name' => $this->customer_text],

            'checkout_station_id' =>  new StationResource($this->checkout_station) ?? $this->checkout_station_id,
            'checkout_place_id' => $this->checkout_place_id,
            'checkout_place_text'=> $this->checkout_place_text,
            //'checkout_station_fee' => $this->checkout_station_fee,
            'checkout_comments' => $this->checkout_comments,
            'checkout_datetime' => $this->checkout_datetime,

            'checkin_datetime' => $this->checkin_datetime,
            'checkin_station_id' => new StationResource($this->checkin_station) ?? $this->checkin_station_id,
            'checkin_place_id' => $this->checkin_place_id,
            'checkin_place_text' =>$this->checkin_place_text,
           // 'checkin_station_fee' => $this->checkin_station_fee,
            'checkin_comments' => $this->checkin_comments,

            'may_extend' => $this->may_extend,
          //  'estimated_km' => $this->estimated_km,
            'valid_date' => $this->valid_date,
            'extension_rate'=>$this->extension_rate,
            'extra_day' =>$this->extra_day,

            'duration' => $this->duration,
         //   'rate' => $this->rate,
          //  'vat_included'=> $this->vat_included,
          //  'distance' => $this->distance,
          //  'distance_rate' => $this->distance_rate,
          //  'transport_fee' => $this->transport_fee,
         //   'insurance_fee' => $this->insurance_fee,
         //   'options_fee' => $this->options_fee,
         //   'fuel_fee' => $this->fuel_fee,
          //  'subcharges_fee' => $this->subcharges_fee,
         //   'rental_fee'=>$this->rental_fee,
          //  'vat_fee'=>$this->vat_fee,
         //   'discount' => $this->discount,
         //   'voucher' => $this->voucher,
          //  'total' => $this->total,
          //  'total_net' => $this->total_net,
          //  'total_paid' => $this->total_paid,
          //  'vat' => $this->vat,
           // 'balance' => $this->balance,
            'comments' => $this->comments,

            'print' => $this->getLastPrintingAttribute(),

            'type' => new TypeResource($this->type),
            'station_checkin' => new StationResource($this->checkin_station),
            'station_checkout' => new StationResource($this->checkout_station),

            'checkout_place' => ['id' => $this->checkout_place_id, 'name' => $this->checkout_place_text],
            'checkin_place' => ['id' => $this->checkin_place_id, 'name' => $this->checkin_place_text],

            'booking_source' => new SourceResource($this->source), //Πηγή
          //  'driver' => new DriverResource($this->customer),
           // 'company' => new CompanyResource($this->company),
            'agent' => new AgentResource($this->agent), //Συνεργάτης
           // 'vehicle' => new VehicleResource($this->vehicle),
            'documents' => DocumentResource::collection($this->documents),
            'tags' => new TagCollection($this->tags),

            'booking' => new BookingResource($this->booking),
            'IamQuote' => '',

            'summary_charges' => [
               // 'manual_agreement' => $this->manual_agreement,
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
                'discount_fee' => ($this->subcharges_fee*  $this->discount)/100 ,
                'discount_percentage' => $this->discount_percentage,
                'vat_included' => $this->vat_included,
                'vat' => $this->vat,
                'voucher' => $this->voucher,

                'total' => $this->total,
                'total_net' => $this->total_net,
                'total_paid' => $this->total_paid,
                'balance' => $this->balance,

                'items' =>BookingItemResource::collection($this->options),
            ],
        ];


        if (!in_array('items', $this->exclude_fields)) {
          //  $array['items'] = BookingItemResource::collection($this->options);
        }
        return $array;
    }

    //  public function mySort($ArrayItems){
    //    return
    //     array_multisort(
    //         array_column((array)$ArrayItems, 'option.order'),
    //         SORT_ASC,
    //         array_column((array)$ArrayItems, 'option.id'),
    //         SORT_ASC,
    //         (array)$ArrayItems
    //     );
    //  }


}