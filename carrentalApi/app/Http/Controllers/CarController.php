<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassTypeCollection;
use App\Http\Resources\DriveTypeCollection;
use App\Http\Resources\FuelTypeCollection;
use App\Http\Resources\OwnershipCollection;
use App\Http\Resources\PeriodicFeeCollection;
use App\Http\Resources\PeriodicFeeResource;
use App\Http\Resources\PeriodicFeeTypeCollection;
use App\Http\Resources\TransmissionTypeCollection;
use App\Http\Resources\UseTypeCollection;
use App\Http\Resources\VehicleCollection;
use App\Http\Resources\VehicleResource;
use App\Http\Resources\LicencePlateResource;
use App\Models\Booking;
use App\Models\ClassTypes;
use App\Models\DriveTypes;
use App\Models\FuelTypes;
use App\Models\Language;
use App\Models\LicencePlate;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\OwnershipTypes;
use App\Models\PeriodicFee;
use App\Models\PeriodicFeeType;
use App\Models\Rental;
use App\Models\Station;
use App\Models\StationProfile;
use App\Models\Transition;
use App\Models\TransmissionTypes;
use App\Models\Type;
use App\Models\UseTypes;
use App\Models\Vehicle;
use App\Models\VehicleProfile;
use App\Models\VehicleReservations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;


class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //note!! licence plates and periodic fees are handled here in car controller, v2 end with api

    public function preview(Request $request, $lng)
    {
        $term = isset($request['search']) ? $request['search'] : null;
        $cars = Vehicle::getSearchQuery($term, $lng)->filter($request)->applyOrder($request);

        $locations  = Location::all();

        if ($request->has('export')) {
            $filename = 'vehicles';
            if ($request->type_id) {
                $filename .= '-' . Type::find($request->type_id[0])->category->international_title;
            }
            if ($request->station_id) {
                $filename .= '-' . Station::find($request->station_id)->code;
            }
            return ExportController::createFileFromCollection($cars->get(), $request['export-field'] ?? [], $filename);
        }

        $cars = $cars->paginate(Cookie::get('pages') ?? '5');

        return view('cars.preview', compact(['cars', 'lng', 'locations']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $term = isset($request['search']) ? $request['search'] : null;
        $group_id   = isset($request['group']) ? (int)$request['group'] : null;
        $from       = isset($request['from']) ? Carbon::parse($request['from']) : null;
        $to         = isset($request['to']) ? Carbon::parse($request['to']) : null;
        $rental_id  = isset($request['rental_id']) ? (int)$request['rental_id'] : null;

        $type_id   = isset($request['type_id']) ? $request['type_id'] : null;//v2 equals group
        if($type_id){
            $group_id=(string)$type_id[0];
        }

        $exclude_vehicle_ids = [];
        if ($from && $to) {
            $exclude_vehicle_ids = VehicleReservations::getReservedVehicles($from->toDate(), $to->toDate(), $rental_id, 'rental');
          array_push($exclude_vehicle_ids, VehicleReservations::getReservedVehicles($from->toDate(), $to->toDate(), $rental_id, 'booking'));
        }


        $cars = Vehicle::getSearchQuery($term, $lng ?? 'el', $group_id, $exclude_vehicle_ids)->filter($request)->applyOrder($request);


            $cars = Vehicle::query()->orderBy('created_at', 'desc');
            if ($term) {
                $vehicle_status   = isset($request['vehicle_status']) ? $request['vehicle_status'] : null;
                $status   = isset($request['status']) ? $request['status'] : null;

                $type_id = is_array($type_id) ? implode("", $type_id) : null;
                $vehicle_status = is_array($vehicle_status) ? implode("", $vehicle_status) : null;
                $status= is_array($status) ? implode("", $status) : null;

                $cars = $cars->whereHas('license_plates',function ($licence_plates_q) use ($term) {
                 $licence_plates_q->where('licence_plate', 'like', '%' . $term . '%');
                 })->orWhere('model', 'like', '%' . $term . '%')->orWhere('make', 'like', '%' . $term . '%');


                if ($type_id && $status) {//has group
                    $cars = $cars->whereHas('license_plates', function ($licence_plates_q) use ($term) {
                        $licence_plates_q->where('licence_plate', 'like', '%' . $term . '%');
                    })->where('type_id', $type_id)
                    ->whereHas('vehicle_status', function ($vehicle_status_q) use ($vehicle_status) {
                        $vehicle_status_q->where('slug', 'like', '%' . $vehicle_status . '%');
                    })->where('status', 'like', '%' . $status . '%');

                } else if($status) { //no group
                    $cars = $cars->whereHas('license_plates', function ($licence_plates_q) use ($term) {
                        $licence_plates_q->where('licence_plate', 'like', '%' . $term . '%');
                    })
                    ->whereHas('vehicle_status', function ($vehicle_status_q) use ($vehicle_status) {
                        $vehicle_status_q->where('slug', 'like', '%' . $vehicle_status . '%');
                    })->where('status', 'like', '%' . $status . '%');
                }
                if($from && $to) { //query comes from rental or booking
                    $pages= $request->get('per_page') ?? '5';
                    $cars = Vehicle::getSearchQuery($term, $lng ?? 'el', $group_id, $exclude_vehicle_ids)->with('group');
                    $cars_in_rentals = Vehicle::whereIn('id', Rental::where('status', Rental::STATUS_ACTIVE)
                        ->where('checkin_datetime', '<=', Carbon::now()->toDateTimeString())
                        ->where('id', '!=', $rental_id)
                        ->pluck('vehicle_id'))->pluck('id')->toArray();

                    $cars_in_bookings = Vehicle::whereIn('id', Booking::where('status', Booking::STATUS_PENDING)
                    ->where('checkin_datetime', '<=',Carbon::now()->toDateTimeString())
                    ->where('id', '!=', $rental_id)
                    ->pluck('vehicle_id'))->pluck('id')->toArray();

                    $cars = $cars->where(function ($q) {
                        $q->where('status_id', 1)->orWhereNull('status_id');
                    });

                    $cars = $cars->take(50)->get();

                    //return 1;
                    /** @var \Illuminate\Support\Collection $cars */
                    $cars->each(function ($car){
                        $car->licence_plates=(LicencePlateResource::collection($car->license_plates));
                        $car->KTEO=$car->getKteoAttribute();
                        $car->insurance = $car->getInsuranceAttribute();
                        $car->type=$car->type;
                      $car->IamVehicle='';
                       // $car->push(['KTEO', $car->getKteoAttribute() ?? '']);
                        // $car->push('insurance',$car->getInsuranceAttribute() ?? '');
                    });

                    /** @var \Illuminate\Support\Collection $cars */
                    $cars->each(function ($car) use ($cars_in_rentals) {
                       // $car->append('licence_plates');
                        if (in_array($car->id, $cars_in_rentals)) {
                            $car->warning = 1;
                            $car->rental=$car->rental();
                        }
                    });

                    /** @var \Illuminate\Support\Collection $cars */
                    $cars->each(function ($car) use ($cars_in_bookings) {
                        // $car->append('licence_plates');
                        if (in_array($car->id, $cars_in_bookings)) {
                            $car->warningB = 1;
                            $car->booking = $car->booking();
                        }
                    });

                    $array = ['data'=>$cars];
                    return response()->json($array);
                }
            }
        return new VehicleCollection($cars->filter($request)->applyOrder($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));

    }


    public function edit(Request $request, $id)
    {
        $car = Vehicle::find($id);

        return new VehicleResource($car);
    }

    public function fuel(Request $request)
    {
        $fuel_types = FuelTypes::query();
        return new FuelTypeCollection($fuel_types->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }


    public function ownership(Request $request)
    {
        $ownership_types = OwnershipTypes::query();
        return new OwnershipCollection($ownership_types->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function use(Request $request)
    {
        $use_types = UseTypes::query();
        return new UseTypeCollection($use_types->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function class(Request $request)
    {
        $class_types = ClassTypes::query();
        return new ClassTypeCollection($class_types->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function periodicFeeTypes(Request $request)
    {
        $periodicFee_types = PeriodicFeeType::query();
        return new PeriodicFeeTypeCollection($periodicFee_types->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function periodicFee(Request $request)
    {
        $periodicFee = PeriodicFee::query();
        return new PeriodicFeeCollection($periodicFee->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function periodicFeeEdit(Request $request,$id)
    {
        $periodic = PeriodicFee::find($id);

        return new PeriodicFeeResource($periodic);

    }

    public function transmission(Request $request)
    {
        $transmission = TransmissionTypes::query();
        return new TransmissionTypeCollection($transmission->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function drive_type(Request $request)
    {
        $drive_type = DriveTypes::query();
        return new DriveTypeCollection($drive_type->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }


    public function search_ajax(Request $data, $lng)
    {
        $pages      = Cookie::get('pages') ?? 5;
        $term       = isset($data['search']) ? $data['search'] : null;
        $group_id   = isset($data['group']) ? (int)$data['group'] : null;
        $from       = isset($data['from']) ? Carbon::parse($data['from']) : null;
        $to         = isset($data['to']) ? Carbon::parse($data['to']) : null;
        $rental_id  = isset($data['rental_id']) ? (int)$data['rental_id'] : null;

        $exclude_vehicle_ids = [];

        if ($from && $to) {
            $exclude_vehicle_ids = VehicleReservations::getReservedVehicles($from->toDate(), $to->toDate(), $rental_id, 'rental');
        }

        $cars = Vehicle::getSearchQuery($term, $lng, $group_id, $exclude_vehicle_ids)->with('group');
        $cars_in_rentals = Vehicle::whereIn('id', Rental::where('status', Rental::STATUS_ACTIVE)
            ->where('checkin_datetime', '<=', Carbon::now()->toDateTimeString())
            ->where('id', '!=', $rental_id)
            ->pluck('vehicle_id'))->pluck('id')->toArray();

        $cars = $cars->where(function ($q) {
            $q->where('status_id', 1)->orWhereNull('status_id');
        });

        $cars = $cars->take($pages)->get();
        /** @var \Illuminate\Support\Collection $cars */
        $cars->each(function ($car) use ($cars_in_rentals) {
            $car->append('licence_plate');
            if (in_array($car->id, $cars_in_rentals)) {
                $car->warning = 1;
            }
        });

        // $cars->each->append('licence_plate');

        return response()->json($cars);
    }

    public function get_data_ajax(Request $data)
    {
        $car = Vehicle::where('id', $data['car'])->first();
        $car->fuel = $car->fuel_level;
        return response()->json($car);
    }

    public function view(Request $request, $lng, $id)
    {
        $fee_types = PeriodicFeeType::all();
        return view('cars.create', [
            'car' => Vehicle::find($id), 'lang' => Language::all(),
            'lng' => $lng, 'fee_types' => $fee_types, 'view' => true
        ]);
    }

    public function create_view(Request $data, $lng)
    {
        $fee_types = PeriodicFeeType::all();

        if (isset($data['cat_id'])) {
            return view('cars.create', [
                'lang'      => Language::all(),
                'car'       => Vehicle::find($data['cat_id']),
                'lng'       => $lng,
                'fee_types' => $fee_types,
                'duplicate' => $data->has('duplicate') && $data->duplicate == true,
            ]);
        }
        return view('cars.create', ['lang' => Language::all(), 'lng' => $lng, 'fee_types' => $fee_types]);
    }

    public function delete(Request $data)
    {
        $cars = Vehicle::whereIn('id', $data['ids'])->delete();
        return "Deleted";
    }

    public function delete_api(Request $data)
    {
            $car = Vehicle::find($data['id']); // v2 sends one by one del requests
            $car->delete();
            return new VehicleResource($car);
    }

    public function update_store_api(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'id'        => 'nullable|numeric',
            'type_id'   => 'required|exists:types,id',
            'vin'       => 'required|unique:vehicles,vin,' . $data['id'],
            'model'      => 'required',
            'make'      => 'required',
            'drive_type_id'      => 'required',
            'engine'    => 'required',
            'power'     => 'nullable',
            'transmission_type_id' => 'required|exists:transmission_types,id',
            'first_licence_plate' => 'nullable|unique:licence_plates,licence_plate',
           'first_licence_plate_date' => 'nullable|date',
            'km'        => 'integer',
            'fuel_level'        => 'integer',
            'station_id.id' => 'exists:stations,id',
            'status_id' => 'exists:vehicle_status,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $car = Vehicle::firstOrNew(['id' => $data['id']]);
        $car->vin = $data['vin'];
        $car->make = $data['make'];
        $car->model = $data['model'];
        $car->type_id = $data['type_id'];
        $car->engine = $data['engine'];
        $car->power = $data['power'];
        $car->hp = $data['hp'];
        $car->drive_type_id = $data['drive_type_id'];
        $car->transmission_type_id = $data['transmission_type_id'];
        if (isset($data['km'])) {
            $car->km = $data['km'];
        }
        // if (isset($data['fuel_level'])) {
        //     $car->fuel_level = $data['fuel_level'];
        // }

        if (is_array($data['station_id'])) {
            $car->station_id = (int)$data['station_id.id'];
        } else {
            $car->station_id = $data['station_id'];
        }
        $car->doors = $data['doors'];
        $car->seats = $data['seats'];
        $car->euroclass = $data['euroclass'];
        $car->color_type_id = $data['color_type_id'];
        //$car->color_code = $data['color_code'];
        $car->warranty_expiration = $data['warranty_expiration'];
        $car->engine_number = $data['engine_number'];
        $car->tank = $data['tank'];
        $car->pollution = $data['pollution'];
        $car->manufactured_year = $data['manufactured_year'];
        $car->radio_code = $data['radio_code'];
        $car->purchase_date = $data['purchase_date'];
        $car->purchase_amount = $data['purchase_amount'];
        $car->buy_back = $data['buy_back'];
        $car->first_date_marketing_authorisation = $data['first_date_marketing_authorisation'];
        $car->first_date_marketing_authorisation_gr = $data['first_date_marketing_authorisation_gr'];
        $car->import_to_system = $data['import_to_system'];
        $car->export_from_system = $data['export_from_system'];
        $car->forecast_export_from_system = $data['forecast_export_from_system'];
        $car->ownership_type_id = $data['ownership'];
        $car->use_type_id = $data['use_type_id'];
        $car->class_type_id = $data['class_type_id'];
        $car->fuel_type_id = $data['fuel_type_id'];
        $car->key_code = $data['key_code'];
        $car->keys_quantity = $data['keys_quantity'];

        $car->depreciation_rate = $data['depreciation_rate'];
        $car->depreciation_rate_year = $data['depreciation_rate_year'];
        $car->sale_amount = $data['sale_amount'];
        $car->sale_date = $data['sale_date'];
        $car->start_stop = ($data['start_stop'] == 'on') ? true : null;

        $car->status_id = $data['status_id'];
        $car->save();

        $car->documents()->sync($data['documents']);
        $car->images()->sync($data['images']);

        if ($car->id) {
            $this->delete_plate_api($data['licence_plates'], $car->id);
        }

       $myLicence=$this->create_plate_api($data, $car->id);
        if ($myLicence) { // will return any validation error occur in the method
            return $myLicence;
        }

        if ($car->id) {
            $this->delete_fee_api($data['periodic_fees'], $car->id);
        }

       $myFee= $this->update_fee_api($data, $car->id);
       if($myFee){ // will return any validation error occur in the method
           return $myFee;
       }

        foreach ($data['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation              = VehicleProfile::firstOrNew(['vehicle_id' => $car->id, 'language_id' => $lang]);
                $translation->vehicle_id  = $car->id;
                $translation->language_id = $lang;
                $translation->title       = $profile['title'];
                $translation->description = $profile['description'];
                $translation->save();
            }
        }

        return new VehicleResource($car);
    }

    public function create(Request $data, $lng)
    {
        //        return "<pre>".print_r($data->all(), 1)."</pre>";
        if (isset($data['id']) && $data['id'] != '') {
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
            $existsVin = $data['vin'];
        } else {
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
            $existsVin = '0';
        }
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'        => 'nullable|numeric',
            'type_id'   => 'required|exists:types,id',
            'vin'       => 'required|unique:vehicles,vin,' . $data['id'],
            'model'      => 'required',
            'make'      => 'required',
            'drive_type'      => 'required',
            'engine'    => 'required',
            'power'     => 'nullable',
            'transmission' => 'required|exists:transmission_types,id',
            'first_licence_plate' => 'nullable|unique:licence_plates,licence_plate',
            'first_licence_plate_date' => 'nullable|date',
            'km'        => 'integer',
            'fuel_level'        => 'integer',
            'station_id' => 'exists:stations,id',
            'status_id' => 'exists:vehicle_status,id'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $car = Vehicle::firstOrNew(['id' => $data['id']]);
        $car->vin = $data['vin'];
        $car->make = $data['make'];
        $car->model = $data['model'];
        $car->type_id = $data['type_id'];
        $car->engine = $data['engine'];
        $car->power = $data['power'];
        $car->hp = $data['hp'];
        $car->drive_type_id = $data['drive_type'];
        $car->transmission_type_id = $data['transmission'];
        if (isset($data['km'])) {
            $car->km = $data['km'];
        }
        if (isset($data['fuel_level'])) {
            $car->fuel_level = $data['fuel_level'];
        }
        if (isset($data['station_id'])) {
            $car->station_id = $data['station_id'];
        }
        $car->doors = $data['doors'];
        $car->seats = $data['seats'];
        $car->euroclass = $data['euroclass'];
        $car->color_type_id = $data['color_title'];
        $car->color_code = $data['color_code'];
        $car->warranty_expiration = $data['warranty_expiration'];
        $car->engine_number = $data['engine_number'];
        $car->tank = $data['tank'];
        $car->pollution = $data['pollution'];
        $car->manufactured_year = $data['manufactured_year'];
        $car->radio_code = $data['radio_code'];
        $car->purchase_date = $data['purchase_date'];
        $car->purchase_amount = $data['purchase_amount'];
        $car->buy_back = $data['buy_back'];
        $car->first_date_marketing_authorisation = $data['first_date_marketing_authorisation'];
        $car->first_date_marketing_authorisation_gr = $data['first_date_marketing_authorisation_gr'];
        $car->import_to_system = $data['import_to_system'];
        $car->export_from_system = $data['export_from_system'];
        $car->forecast_export_from_system = $data['forecast_export_from_system'];
        $car->ownership_type_id = $data['ownership'];
        $car->use_type_id = $data['use'];
        $car->class_type_id = $data['class'];
        $car->fuel_type_id = $data['fuel_type'];
        $car->key_code = $data['key_code'];
        $car->keys_quantity = $data['keys_quantity'];

        $car->depreciation_rate = $data['depreciation_rate'];
        $car->depreciation_rate_year = $data['depreciation_rate_year'];
        $car->sale_amount = $data['sale_amount'];
        $car->sale_date = $data['sale_date'];
        $car->start_stop = ($data['start_stop'] == 'on') ? true : null;

        $car->status_id = $data['status_id'];
        $car->save();

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation              = VehicleProfile::firstOrNew(['vehicle_id' => $car->id, 'language_id' => $lang]);
                $translation->vehicle_id  = $car->id;
                $translation->language_id = $lang;
                $translation->title       = $data['title'][$lang];
                $translation->description = $data['description'][$lang];
                $translation->save();
            }
        }

        if (isset($data['first_licence_plate']) && isset($data['first_licence_plate_date']))
            LicencePlate::create([
                'licence_plate'     => str_replace(' ', '', $data['first_licence_plate']),
                'registration_date' => $data['first_licence_plate_date'],
                'vehicle_id'        => $car->id
            ]);

        return redirect()->route('edit_car_view', ['locale' => $lng, 'cat_id' => $car->id]);
    }

    public function delete_plate(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'item' => 'required',
            'item.id' => 'required'
        ]);
        if ($validator->fails()) {
            return json_encode($validator->errors()->first());
        }
        LicencePlate::find($data['item.id'])->delete();
        return 1;
    }


    public function delete_plate_api($data,$vehicle_id)
    {
        LicencePlate::where('vehicle_id',$vehicle_id)->whereNotIn('id',array_column($data,'id'))->get()->each->delete();
    }

    public function delete_fee_api($data, $vehicle_id)
    {
         PeriodicFee::where('vehicle_id',$vehicle_id)->whereNotIn('id',array_column($data,'id'))->get()->each->delete();
    }

    public function create_plate(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            $existValue = $data['id'];
        else
            $existValue = '';
        $validator = Validator::make($data->all(), [
            'id'       => 'nullable|exists:licence_plates,id',
            'number'   => 'required|unique:licence_plates,licence_plate,' . $existValue,
            'date'     => 'required|date',
            'car_id'   => 'required'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous() . '#licence-tab')->withErrors($validator->errors()->first());
        }
        $lp = LicencePlate::firstOrNew(['id' => $data['id']]);
        $lp->licence_plate = str_replace(' ', '', $data['number']);
        $lp->registration_date = $data['date'];
        $lp->vehicle_id = $data['car_id'];
        $lp->save();
        $lp->addDocuments();

        return Redirect::to(URL::previous() . '#licence-tab');
    }


    public function create_plate_api(Request $data,$vehicle_id)
    {

        foreach ($data['licence_plates'] as $d) {

            if ($d['id'] != '') {
                $existValue = $d['id'];
            } else {
                $existValue = '';
            }
            $validator = Validator::make($d, [
                'id'       => 'nullable|exists:licence_plates,id',
                'licence_plate'   => 'required|unique:licence_plates,licence_plate,' . $existValue,
                'registration_date'     => 'required|date',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(), 400);
            }

            $lp = LicencePlate::firstOrNew(['id' => $d['id']]);
            $lp->licence_plate =  $d['licence_plate'];
            $lp->registration_date = $d['registration_date'];
            $lp->vehicle_id = $vehicle_id;
            $lp->save();
            $lp->documents()->sync($d['documents']);
        }

    }

    public function update_fee_api(Request $data,$vehicle_id)
    {
        foreach ($data['periodic_fees'] as $d) {
            $validator = Validator::make($d, [
                'periodic_fee_type_id' => 'required',
                'title'             => 'required',
                'fee'               => 'nullable',
                'date_start'        => 'required|date|before:date_expiration',
                'date_expiration'   => 'required|date',
                'date_payed'        => 'nullable'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(), 400);
            }

        $fee = PeriodicFee::firstOrNew(['id' => $d['id']]);
        $fee->title                 = $d['title'];
        $fee->periodic_fee_type_id  = $d['periodic_fee_type_id'];
        $fee->description           = $d['description'];
        $fee->fee                   = $d['fee'];
        $fee->date_start            = $d['date_start'];
        $fee->date_expiration       = $d['date_expiration'];
        $fee->date_payed            = $d['date_payed'];
        $fee->vehicle_id           = $vehicle_id;

        $fee->save();
        $fee->documents()->sync($d['documents']);
        }

    }

    public function delete_car_image(Request $data)
    {
        // VehicleImage::where('image_id', $data['id'])->delete();
        return "ok";
    }

    public function create_fee(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'type'              => 'required',
            'title'             => 'required',
            'fee'               => 'nullable',
            'date_start'        => 'required|date|before:date_expiration',
            'date_expiration'   => 'required|date',
            'date_payed'        => 'nullable'
        ]);
        if ($validator->fails()) {
            return ($validator->errors()->first());
        }
        $new_fee                        = new PeriodicFee();
        $new_fee->title                 = $data['title'];
        $new_fee->periodic_fee_type_id  = $data['type'];
        $new_fee->vehicle_id            = $data['car_id'];
        $new_fee->description           = $data['description'];
        $new_fee->fee                   = $data['fee'];
        $new_fee->date_start            = $data['date_start'];
        $new_fee->date_expiration       = $data['date_expiration'];
        $new_fee->date_payed            = $data['date_payed'];
        $new_fee->save();
        $new_fee->addDocuments();

        Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        return redirect()->back();
    }

    public function update_fee(Request $data)
    {
        //        return json_encode($data['fee.id']);
        $validator = Validator::make($data->all(), [
            'fee'              => 'required',
            'fee.id'              => 'required',
            'fee.type'              => 'required',
            'fee.title'             => 'required',
            'fee.fee'               => 'nullable',
            'fee.date_start'        => 'required|date|before:fee.date_expiration',
            'fee.date_expiration'   => 'required|date',
            'fee.date_payed'        => 'nullable'
        ]);
        if ($validator->fails()) {
            return ($validator->errors()->first());
        }
        $fee = PeriodicFee::find($data['fee.id']);
        $fee->title                 = $data['fee.title'];
        $fee->periodic_fee_type_id  = $data['fee.type'];
        $fee->description           = $data['fee.description'];
        $fee->fee                   = $data['fee.fee'];
        $fee->date_start            = $data['fee.date_start'];
        $fee->date_expiration       = $data['fee.date_expiration'];
        $fee->date_payed            = $data['fee.date_payed'];
        $fee->save();
        $fee->addDocuments();


        return "<p class='alert alert-success' role='alert'>" . __('Ενημερώθηκε') . "
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                    <span aria-hidden=\"true\">&times;</span>
                  </button>
                </p>";
    }

    public function delete_fee(Request $data)
    {
        $fee = PeriodicFee::find($data['item.id'])->delete();
        return 1;
    }

    public function choose_location(Request $data, $lng)
    {
        $stations = StationProfile::whereHas('station', function ($q) use ($data) {
            $q->where('location_id', $data['id']);
        })->where('language_id', $lng)->get();
        return ($stations);
    }

    public function transfer_cars(Request $data)
    {
        foreach ($data['ids'] as $id) {
            $new_transfer                   = new Transition();
            $new_transfer->user_id          = Auth::id();
            $new_transfer->vehicle_id       = $id;
            $new_transfer->type_id          = $data['type'];
            $new_transfer->station_id_to    = $data['location'];

            $current_station = Transition::where('vehicle_id', $id)->orderBy('created_at', 'desc')->first();
            if ($current_station) {
                $new_transfer->station_id_from = $current_station->station_id_to;
            }

            $new_transfer->save();
            //            echo $new_transfer;
        }
        return __('Η μεταφορές ολοκληρώθηκαν');
    }

    public function transfer_car(Request $data)
    {

        return "<pre>" . print_r($data->all(), 1) . "</pre>";
    }

    public function display_maintenances(Request $data)
    {
        $response = Maintenance::select('type', 'id')
            ->where('vehicle_id', $data['car'])
            ->where('end', null)
            ->orderBy('created_at', 'desc')
            ->groupBy('type', 'created_at', 'id')
            ->get();
        return ($response);
    }

    public function update_maintenances(Request $data)
    {
        $mytime = Carbon::now();
        $maintenance = Maintenance::select('type', 'id')
            ->where('vehicle_id', $data['car'])
            ->where('end', null)
            ->where('type', $data['type'])
            ->orderBy('created_at', 'desc', 'id')
            ->first();
        if ($maintenance === null) {
            $maintenance                = new Maintenance();
            $maintenance->user_id       = Auth::id();
            $maintenance->vehicle_id    = $data['car'];
            $maintenance->type          = $data['type'];
            $maintenance->start         = $mytime->toDateTimeString();
            $maintenance->save();
            return 1;
        } else {
            $maintenance->end           = $mytime->toDateTimeString();
            $maintenance->save();
            return 0;
        }
    }

    public function photoshoot(Request $data, $lng)
    {
        if (isset($data['booking'])) {
            $booking = $data['booking'];
            return view('cars.photoshoot', compact('booking'));
        } else return redirect()->route('home', $lng);
    }

    public function document_upload_image(Request $data)
    {
        //      $data['webcam']->store('public');
        $file =  $data['webcam'];
        $booking = Booking::find($data['booking']);
        if ($booking === null)
            return 0;

        $booking->addDocumentFromUploadedFile($file);
        return 1;
    }
}
