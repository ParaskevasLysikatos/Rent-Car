<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExchangeVehicleCreateRequest;
use App\Http\Resources\VehicleExchangeCollection;
use App\Http\Resources\VehicleExchangeResource;
use App\Models\Rental;
use App\Models\Vehicle;
use App\Models\VehicleExchange;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class VehicleExchangeController extends Controller
{
    public function create(Request $data, $lng, $rental_id)
    {
        $rental = Rental::find($rental_id);
        $exchange = $rental->exchanges()->where('status', '!=', VehicleExchange::STATUS_COMPLETED)->first();
        $vars = ['lng', 'rental'];
        if ($exchange) {
            $vars[] = 'exchange';
        }
        if (!$rental) {
            throw new Exception('Rental doesn\'t exist');
        }
        return view('transfers.exchange-vehicle', compact($vars));
    }

    public function edit(Request $request, $lng)
    {
        $exchange = VehicleExchange::find($request['cat_id']);
        $rental = $exchange->rental;
        $vars = ['lng', 'rental'];
        if ($exchange) {
            $vars[] = 'exchange';
            if ($exchange->status == VehicleExchange::STATUS_COMPLETED) {
                $view = true;
                $vars[] = 'view';
            }
        }
        if (!$rental) {
            throw new Exception('Rental doesn\'t exist');
        }
        return view('transfers.exchange-vehicle', compact($vars));
    }

    public function preview_api(Request $data)
    {
        $term = isset($data['search']) ? $data['search'] : null;
        if ($data->wantsJson()) {
            $vehicle_exchanges = VehicleExchange::query()->orderBy('created_at', 'desc');

            if ($term) {
                $vehicle_exchanges = $vehicle_exchanges
               ->
                whereHas('new_vehicle', function ($q) use ($term) {
                    $q->whereHas('license_plates', function ($q2) use ($term) {
                        $q2->where('vehicle_id', 'like', '%' . $term . '%');
                    });
                })
                -> orWhereHas('old_vehicle', function ($q) use ($term) {
                    $q->whereHas('license_plates', function ($q2) use ($term) {
                        $q2->where('vehicle_id', 'like', '%' . $term . '%');
                    });
                })
               ->orWhereHas('rental', function ($vehicle_rental_q) use ($term) {
                   $vehicle_rental_q->where('sequence_number', 'like', '%' . $term . '%');
                });
            }
            return new VehicleExchangeCollection($vehicle_exchanges->filter($data)->applyOrder($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
        }
    }


    public function preview(Request $request, $lng)
    {
        Route::current()->middleware();
        $vehicle_exchanges = VehicleExchange::query();

        $vehicle_exchanges = $vehicle_exchanges->filter($request)->applyOrder($request)->paginate(Cookie::get('pages') ?? '5');
        return view('vehicle_exchanges.preview', compact(['vehicle_exchanges', 'lng']));
    }

    public function edit_api(Request $request, $id)
    {
        $vehicle_exchanges = VehicleExchange::find($id);

        return new VehicleExchangeResource($vehicle_exchanges);
    }

    public function delete_api(Request $request, $id)
    {
        $vehicle_exchanges = VehicleExchange::find($id);
        $vehicle_exchanges->delete();
        return new VehicleExchangeResource($vehicle_exchanges);
    }

    public function store_outside(VehicleExchange $exchange, ExchangeVehicleCreateRequest $request, Rental $rental)
    {
        if (
            $exchange->new_vehicle_id && $request['departure_datetime']
            && $request['driver_id'] && $request['new_co_km'] && $request['new_co_fuel_level']
            && $request['new_vehicle_id']
        ) {
            $exchange->status = VehicleExchange::STATUS_PROCESSING;
            if ($request['replaced_km'] && $request['replaced_fuel_level']) {
                $exchange->status = VehicleExchange::STATUS_COMPLETED;
            }
        }

        $transitionController = new TransferController();

        $common = [
            'id' => '',
            'driver' => 'employee',
            'driver_id' => null,
            'driver_employee_id' => $request['driver_id'],
            'transfer_ci_notes' => '',
            'transfer_co_notes' => '',
            'transfer_co_user_id' => Auth::id(),
            'transferType' => 2,
            'transfer_notes' => $request['transfer_notes'],
            'rental_id' => $rental->id
        ];

        $new_vehicle = Vehicle::find($request['new_vehicle_id']);
        if (
            $exchange->status == VehicleExchange::STATUS_PROCESSING
            || $exchange->status == VehicleExchange::STATUS_COMPLETED
        ) {
            $new = array_merge($common, [
                'transfer_ci_km' => $request['new_ci_km'],
                'transfer_ci_fuel' => $request['new_ci_fuel_level'],
                'transfer_ci_station' => $rental->checkout_station_id,
                'transfer_ci_place_id' => $request['place_id'],
                'transfer_ci_place_text' => $request['place_text'],
                'transfer_ci_datetime' => $request['datetime'],
                'vehicle_id' => $request['new_vehicle_id'],
                'transfer_co_datetime' => $request['departure_datetime'],
                'transfer_co_station' => $new_vehicle->station_id ?? config('preferences.station_id'),
                'transfer_co_place_id' => null,
                'transfer_co_place_text' => null,
                'transfer_co_km' => $request['new_co_km'],
                'transfer_co_fuel' => $request['new_co_fuel_level'],
            ]);
            if ($exchange->new_vehicle_transition_id) {
                $new['id'] = $exchange->new_vehicle_transition_id;
            }
            $transition = $transitionController->create_transfer($new);
            $exchange->new_vehicle_transition_id = $transition->id;
        }
        if ($exchange->status == VehicleExchange::STATUS_COMPLETED && !$exchange->old_vehicle_transition_id) {
            $replaced = array_merge($common, [
                'transfer_ci_km' => null,
                'transfer_ci_fuel' => null,
                'transfer_ci_datetime' => null,
                'transfer_ci_station' => null,
                'transfer_ci_place_id' => null,
                'transfer_ci_place_text' => null,
                'vehicle_id' => $request['replaced_vehicle_id'],
                'transfer_co_datetime' => $request['datetime'],
                'transfer_co_station' => $rental->checkout_station_id,
                'transfer_co_place_id' => $request['place_id'],
                'transfer_co_place_text' => $request['place_text'],
                'transfer_co_km' => $request['replaced_km'],
                'transfer_co_fuel' => $request['replaced_fuel_level'],
            ]);
            $transition = $transitionController->create_transfer($replaced);
            $exchange->old_vehicle_transition_id = $transition->id;

            $exchange->old_vehicle_rental_ci_km = $request['replaced_km'];
            $exchange->old_vehicle_rental_ci_fuel_level = $request['replaced_fuel_level'];

            $exchange->new_vehicle_rental_co_km = $request['new_ci_km'];
            $exchange->new_vehicle_rental_co_fuel_level = $request['new_ci_fuel_level'];

            $old_vehicle_transition = $exchange->new_vehicle_transition()->first();
            $old_vehicle_transition->ci_datetime = $request['datetime'];
            $old_vehicle_transition->save();

            $rental->vehicle_id = $new_vehicle->id;
            $rental->checkout_km = $request['new_ci_km'];
            $rental->checkout_fuel_level = $request['new_ci_fuel_level'];
            $rental->save();
        }

        $exchange->save();

        return $exchange;
    }

    public function store(ExchangeVehicleCreateRequest $request, $lng, $rental_id)
    {
        $rental = Rental::find($rental_id);
        if ($rental->vehicle_id != $request['replaced_vehicle_id']) {
            throw new Exception('Rental vehicle does\'t match vehicle');
        }

        $exchange = VehicleExchange::firstOrNew(['id' => $request['id']]);
        $exchange->old_vehicle_id                       = $rental->vehicle_id;
        $exchange->driver_id                            = $request['driver_id'];
        $exchange->new_vehicle_type_id                  = $request['new_vehicle_type_id'];
        $exchange->new_vehicle_id                       = $request['new_vehicle_id'];
        $exchange->rental_id                            = $request['rental_id'];
        $exchange->proximate_datetime                   = $request['proximate_datetime'];
        $exchange->datetime                             = $request['datetime'];
        $exchange->place_id                             = $request['place_id'];
        $exchange->place_text                           = $request['place_text'];
        $exchange->type                                 = $request['type'];
        $exchange->reason                               = $request['reason'] ?? 'REASON';
        $exchange->old_vehicle_rental_co_km             = $rental->checkout_km;
        $exchange->old_vehicle_rental_co_fuel_level     = $rental->checkout_fuel_level;

        if ($exchange->type == VehicleExchange::TYPE_OFFICE) {
            $exchange->station_id = $request['station_id'];
            $exchange->old_vehicle_rental_ci_km = $request['replaced_km'];
            $exchange->old_vehicle_rental_ci_fuel_level = $request['replaced_fuel_level'];

            $exchange->new_vehicle_rental_co_km = $request['new_ci_km'];
            $exchange->new_vehicle_rental_co_fuel_level = $request['new_ci_fuel_level'];

            $exchange->status = VehicleExchange::STATUS_COMPLETED;
            $exchange->save();

            $rental->vehicle_id = $request['new_vehicle_id'];
            $rental->checkout_km = $request['new_ci_km'];
            $rental->checkout_fuel_level = $request['new_ci_fuel_level'];
            $rental->save();

            $old_vehicle = Vehicle::find($exchange->old_vehicle_id);
            $old_vehicle->station_id = $request['station_id'];
            $old_vehicle->km = $request['replaced_km'];
            $old_vehicle->fuel_level = $request['replaced_fuel_level'];
            $old_vehicle->save();
        } else if ($exchange->type == VehicleExchange::TYPE_OUTSIDE) {
            $this->store_outside($exchange, $request, $rental);
        }

        return redirect()->route('edit_rental_view', ['locale' => $lng, 'cat_id' => $rental->id]);
    }


    public function store_update_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'datetime'       => 'required|date',
            'station_id.id'  => 'required',
            'place'      => 'required',
            'new_vehicle_id'      => 'required',
            'driver_id'   => 'required',
            'new_vehicle_rental_co_km'   => 'required|numeric',
            'old_vehicle_id'   => 'required',
            'old_vehicle_rental_co_km'   => 'required|numeric',
            'old_vehicle_rental_ci_km'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $rental = Rental::find($request['rental_id']);
        if ($rental->vehicle_id != $request['old_vehicle_id']) {
            throw new Exception('Rental vehicle does\'t match vehicle');
        }
        $exchange = VehicleExchange::firstOrNew(['id' => $request['id']]);
        $exchange->old_vehicle_id                       = $rental->vehicle_id;
        $exchange->driver_id                            = $request['driver_id'];
        $exchange->new_vehicle_type_id                  = $request['new_vehicle_type_id'];
        $exchange->new_vehicle_id                       = $request['new_vehicle_id'];
        $exchange->rental_id                            = $request['rental_id'];
        $exchange->proximate_datetime                   = $request['proximate_datetime'];
        $exchange->datetime                             = $request['datetime'];
        $exchange->place_id                             = $request['place.id'];
        $exchange->place_text                           = $request['place.name'];
        $exchange->type                                 = $request['type'];
        $exchange->reason                               = $request['reason'] ?? 'REASON';
        $exchange->old_vehicle_rental_co_km             = $rental->checkout_km;
        $exchange->old_vehicle_rental_co_fuel_level     = $rental->checkout_fuel_level;


        if ($exchange->type == VehicleExchange::TYPE_OFFICE) {
            $exchange->station_id = $request['station_id.id'];
            $exchange->old_vehicle_rental_ci_km = $request['old_vehicle_rental_ci_km']; //$request['replaced_km'];
            $exchange->old_vehicle_rental_ci_fuel_level = $request['old_vehicle_rental_ci_fuel_level']; //$request['replaced_fuel_level'];

            $exchange->new_vehicle_rental_co_km = $request['new_vehicle_rental_co_km']; // $request['new_ci_km'];
            $exchange->new_vehicle_rental_co_fuel_level = $request['new_vehicle_rental_co_fuel_level']; //$request['new_ci_fuel_level'];

            $exchange->status = VehicleExchange::STATUS_COMPLETED;
            $exchange->save();
            $exchange->addDocuments();
            $exchange->documents()->sync($request['documents']);
            $exchange->save();


            $rental->type_id = $request['new_vehicle_type_id'];
            $rental->vehicle_id = $request['new_vehicle_id'];
            $rental->checkout_km = $request['new_vehicle_rental_co_km']; // $request['new_ci_km'];;
            $rental->checkout_fuel_level = $request['new_vehicle_rental_co_fuel_level']; //$request['new_ci_fuel_level'];

            $rental->checkin_km = $request['new_vehicle_rental_co_km']; // $request['new_ci_km'];;
            $rental->checkin_fuel_level = $request['new_vehicle_rental_co_fuel_level']; //$request['new_ci_fuel_level'];
            $rental->save();

            $old_vehicle = Vehicle::find($exchange->old_vehicle_id);
            $old_vehicle->station_id = $request['station_id.id'];
            $old_vehicle->km = $request['old_vehicle_rental_ci_km']; //$request['replaced_km'];
            $old_vehicle->fuel_level = $request['old_vehicle_rental_ci_fuel_level']; //$request['replaced_fuel_level'];
            $old_vehicle->save();
        } else if ($exchange->type == VehicleExchange::TYPE_OUTSIDE) {
            $this->store_outside_api($exchange, $request, $rental);
        }

        return new VehicleExchangeResource($exchange);
    }


    public function store_outside_api(VehicleExchange $exchange, Request $request, Rental $rental)
    {
        if (
            $exchange->new_vehicle_id && $request['datetime'] //$request['departure_datetime']
            && $request['driver_id'] &&
            $request['new_vehicle_rental_co_km'] //$request['new_co_km']
            && $request['new_vehicle_rental_co_fuel_level'] //$request['new_co_fuel_level']
            && $request['new_vehicle_id']
        ) {
            $exchange->status = VehicleExchange::STATUS_PROCESSING;
            if ($request['old_vehicle_rental_ci_km'] && $request['old_vehicle_rental_ci_fuel_level']) {
                $exchange->status = VehicleExchange::STATUS_COMPLETED;
            }
        }

        $transitionController = new TransferController();

        $common = [
            'id' => '',
            'driver' => 'employee',
            'driver_id' => null,
            'driver_employee_id' => $request['driver_id'],
            'transfer_ci_notes' => '',
            'transfer_co_notes' => '',
            'transfer_co_user_id' => Auth::id(),
            'transferType' => 2,
            'transfer_notes' => $request['reason'], // $request['transfer_notes']
            'rental_id' => $rental->id
        ];

        $new_vehicle = Vehicle::find($request['new_vehicle_id']);
        if (
            $exchange->status == VehicleExchange::STATUS_PROCESSING
            || $exchange->status == VehicleExchange::STATUS_COMPLETED
        ) {
            $new = array_merge($common, [
                'transfer_ci_km' =>  $request['new_vehicle_rental_co_km'], // $request['new_ci_km'],
                'transfer_ci_fuel' => $request['new_vehicle_rental_co_fuel_level'], //$request['new_ci_fuel_level'],
                'transfer_ci_station' => $rental->checkout_station_id,
                'transfer_ci_place_id' => $request['place_id'],
                'transfer_ci_place_text' => $request['place_text'],
                'transfer_ci_datetime' => $request['datetime'],
                'vehicle_id' => $request['new_vehicle_id'],
                'transfer_co_datetime' => $request['datetime'], //$request['departure_datetime']
                'transfer_co_station' => $new_vehicle->station_id ?? config('preferences.station_id'),
                'transfer_co_place_id' => null,
                'transfer_co_place_text' => null,
                'transfer_co_km' => $request['new_vehicle_rental_co_km'], // $request['new_co_km'],
                'transfer_co_fuel' => $request['new_vehicle_rental_co_fuel_level'], //$request['new_co_fuel_level'],
            ]);
            if ($exchange->new_vehicle_transition_id) {
                $new['id'] = $exchange->new_vehicle_transition_id;
            }
            $transition = $transitionController->create_transferArray($new);
            $exchange->new_vehicle_transition_id = $transition->id;
        }
        if ($exchange->status == VehicleExchange::STATUS_COMPLETED && !$exchange->old_vehicle_transition_id) {
            $replaced = array_merge($common, [
                'transfer_ci_km' => null,
                'transfer_ci_fuel' => null,
                'transfer_ci_datetime' => null,
                'transfer_ci_station' => null,
                'transfer_ci_place_id' => null,
                'transfer_ci_place_text' => null,
                'vehicle_id' => $request['old_vehicle_id'], //$request['replaced_vehicle_id'],
                'transfer_co_datetime' => $request['datetime'],
                'transfer_co_station' => $rental->checkout_station_id,
                'transfer_co_place_id' => $request['place_id'],
                'transfer_co_place_text' => $request['place_text'],
                'transfer_co_km' => $request['old_vehicle_rental_ci_km'], //$request['replaced_km'];
                'transfer_co_fuel' => $request['old_vehicle_rental_ci_fuel_level'], //$request['replaced_fuel_level'];
            ]);
            $transition = $transitionController->create_transfer_api($replaced);
            $exchange->old_vehicle_transition_id = $transition->id;

            $exchange->old_vehicle_rental_ci_km = $request['old_vehicle_rental_ci_km']; //$request['replaced_km'];
            $exchange->old_vehicle_rental_ci_fuel_level = $request['old_vehicle_rental_ci_fuel_level']; //$request['replaced_fuel_level'];

            $exchange->new_vehicle_rental_co_km = $request['new_vehicle_rental_co_km']; // $request['new_ci_km'];
            $exchange->new_vehicle_rental_co_fuel_level = $request['new_vehicle_rental_co_fuel_level']; //$request['new_ci_fuel_level'];

            $old_vehicle_transition = $exchange->new_vehicle_transition()->first();
            $old_vehicle_transition->ci_datetime = $request['datetime'];
            $old_vehicle_transition->save();

            $rental->vehicle_id = $new_vehicle->id;
            $rental->checkout_km = $request['new_vehicle_rental_co_km']; // $request['new_ci_km'];
            $rental->checkout_fuel_level = $request['new_vehicle_rental_co_fuel_level']; //$request['new_ci_fuel_level'];
            $rental->save();
        }

        $exchange->save();

        return $exchange;
    }
}
