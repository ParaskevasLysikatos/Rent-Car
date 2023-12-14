<?php

namespace App\Http\Resources;

use App\Models\PeriodicFee;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB ;
class VehicleResource extends JsonResource
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
            'documents'=> DocumentResource::collection($this->documents),
            'images' => ImageResource::collection($this->images),
            'licence_plates' => LicencePlateResource::collection($this->license_plates),
            'make' => $this->make,
            'model' => $this->model,
            'km' => $this->km,
            'power' => $this->power,
            'engine' => $this->engine,
            'hp' => $this->hp,
            'drive_type' => $this->drive_type,
            'drive_type_id' => $this->drive_type_id,
            'transmission' => $this->transmission,
            'transmission_type_id' => $this->transmission_type_id,
            'fuel_level' => $this->fuel_level,

            'key_code' => $this->key_code,
            'keys_quantity' => $this->keys_quantity,
            'doors' => $this->doors,
            'seats' => $this->seats,
            'euroclass' => $this->euroclass,

            // 'type' => $this->type ? new TypeResource($this->type) : null,
            'type_id' => $this->type_id,
            'type'=>$this->group,
            //'station' => $this->station ? new StationResource($this->station) : null,
            'station_id' => new StationResource($this->station) ?? $this->station_id,
           // 'place' => $this->place ? new PlaceResource($this->place) : null,
            'place_id' => $this->place_id,
            'place_text' => $this->place_text,
            'place' => ['id' => $this->place_id, 'name' => $this->place_text],
            // 'insurance' => $this->insurance ? new OptionResource($this->insurance) : null,
            'vin' => $this->vin,
            'status'=>$this->status,
            'status_id'=>$this->status_id,//vehicle-status-id
            'profiles' => ProfileResource::collection($this->profiles),
            'vehicle_status' => $this->vehicle_status ? new VehicleStatusResource($this->vehicle_status) : null,
            'vehicle_statuses'=>$this->vehicle_statuses ?? null,

            'fuel_type_id'=> $this->fuel_type_id,
            'color_type_id'=> $this->color_type_id,
            'hex_code'=>$this->color_type,
            'ownership_type_id'=> $this->ownership_type_id,
            'class_type_id'=> $this->class_type_id,
            'use_type_id'=> $this->use_type_id,
            'warranty_expiration'=> $this->warranty_expiration,
            'purchase_date'=> $this->purchase_date,

            'engine_number'=> $this->engine_number,
            'tank'=> $this->tank,
            'pollution'=> $this->pollution,
            'radio_code'=> $this->radio_code,
            'purchase_amount'=> $this->purchase_amount,
            'depreciation_rate'=> $this->depreciation_rate,//aposvesi
            'depreciation_rate_year'=> $this->depreciation_rate_year,
            'sale_amount'=> $this->sale_amount,
            'sale_date'=> $this->sale_date,
            'start_stop'=> $this->start_stop,//system of battery
            'buy_back'=> $this->buy_back,
            'first_date_marketing_authorisation'=> $this->first_date_marketing_authorisation,
            'first_date_marketing_authorisation_gr'=> $this->first_date_marketing_authorisation_gr,
            'import_to_system'=> $this->import_to_system,
            'export_from_system'=> $this->export_from_system,
            'forecast_export_from_system'=> $this->forecast_export_from_system,
            'manufactured_year'=> $this->manufactured_year,

            'periodic_fees' => PeriodicFeeResource::collection($this->fees),
            'KTEO' => $this->getKteoAttribute(),
            'insurance' => $this->getInsuranceAttribute(),
            'IamVehicle'=>''
        ];
    }
}
