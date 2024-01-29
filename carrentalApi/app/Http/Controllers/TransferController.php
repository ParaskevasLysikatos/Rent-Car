<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransitionCollection;
use App\Http\Resources\TransitionResource;
use App\Http\Resources\TransitionTypeCollection;
use App\Models\Transition;
use App\Models\TransitionType;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview($lng)
    {
        $transfers = Transition::orderBy('created_at', 'desc')->paginate(Cookie::get('pages') ?? '5');
        return view('transfers.preview', compact(['transfers', 'lng']));
    }


    public function preview_api(Request $data)
    {
            $transfers = Transition::query()->orderBy('created_at', 'desc');
            return new TransitionCollection($transfers->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }

    public function delete_api(Request $data)
    {
        $transition = Transition::find($data['id']); // v2 sends one by one
        $transition->delete();
        return new TransitionResource($transition);
    }


    public function delete(Request $data)
    {
        Transition::whereIn('id', $data['ids'])->get()->each(function ($transition) {
            $transition->delete();
        });
        return "Deleted";
    }

    public function edit(Request $request, $id)
    {
        $transition = Transition::find($id);

        return new TransitionResource($transition);
    }

    public function transition_type(Request $request)
    {
        $transition_type = TransitionType::query();
        return new TransitionTypeCollection($transition_type->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }



    public function create_view(Request $data, $lng){
        if(isset($data['cat_id'])){
            return view('transfers.create', ['lng' => $lng, 'transfer' => Transition::find($data['cat_id'])]);
        }

        if(isset($data['car'])){
            $car = Vehicle::where('id', $data['car'])->first();
            return view('transfers.create', ['lng' => $lng, 'car' => $car]);
        }
        return view('transfers.create', ['lng' => $lng]);
    }

    public function search(Request $data, $lng) {
        $mystring = $data['search'];
        $transfers = Transition::whereHas('vehicle', function (Builder $q) use ($mystring) {
            $q->whereHas('license_plates', function (Builder $license_q) use ($mystring) {
                $license_q->where('licence_plate', 'like', "%" . $mystring . "%");
            });
        })->orWhereHas('co_user', function(Builder $q) use ($mystring) {
            $q->where('name', 'like', '%'.$mystring.'%');
        })->orWhereHas('ci_user', function(Builder $q) use ($mystring) {
            $q->where('name', 'like', '%'.$mystring.'%');
        })
        ->orWhereHas('driver', function(Builder $q) use ($mystring) {
            $q->where('firstname', 'like', '%'.$mystring.'%')->orWhere('lastname', 'like', '%'.$mystring.'%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(Cookie::get('pages') ?? '5');
        return view('transfers.preview', compact(['transfers', 'lng']));
    }

    public function transfer_car(Request $data, $lng){
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');
        $validator = Validator::make($data->all(), [
            'id'            => 'nullable|numeric',
            'transferType'  => 'required',
            'driver'        => 'required',
            'driver_id'     => 'nullable|exists:drivers,id',
            'vehicle_id'     => 'required|exists:vehicles,id',
            'transfer_co_datetime' => 'required',
            'transfer_co_station'   => 'required|exists:stations,id',
            'transfer_co_place_id'  => 'nullable|exists:places,id',
            'transfer_co_km'        => 'required',
            'transfer_co_fuel'      => 'required',
            'transfer_co_user_id'   => 'required|exists:users,id',
            'transfer_ci_datetime'  => 'required',
            'transfer_ci_station'   => 'nullable|exists:stations,id',
            'transfer_ci_place_id'  => 'nullable|exists:places,id'
        ]);
        if ($validator->fails()) {
            return  $validator->errors()->first();
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $tr = $this->create_transfer($data);
        $tr->addDocuments();

    return redirect()->route('create_transfer_view', ['locale' => $lng, 'cat_id'=>$tr->id]);

//        return "<pre>".print_r($data->all(),1)."</pre>";
    }

    public function create_transfer(Request $data) {
        $tr = Transition::firstOrNew(['id' => $data['id']]);

        if($data['driver'] == 'employee') {
            $tr->driver_id = $data['driver_employee_id'];
        }else{
            $tr->driver_id = $data['driver_id'];
        }

        $tr->ci_user_id     = Auth::id();
        $tr->ci_notes       = $data['transfer_ci_notes'];
        $tr->ci_km          = $data['transfer_ci_km'];
        $tr->ci_fuel_level  = $data['transfer_ci_fuel'];
        $tr->ci_datetime    = $data['transfer_ci_datetime'];
        $tr->ci_station_id  = $data['transfer_ci_station'];
        $tr->ci_place_id    = $data['transfer_ci_place_id'];
        $tr->ci_place_text  = $data['transfer_ci_place_text'];
        $tr->vehicle_id     = $data['vehicle_id'];
        $tr->co_user_id     = $data['transfer_co_user_id'];
        $tr->co_datetime    = $data['transfer_co_datetime'];
        $tr->co_station_id  = $data['transfer_co_station'];
        $tr->co_place_id    = $data['transfer_co_place_id'];
        $tr->co_place_text  = $data['transfer_co_place_text'];
        $tr->co_notes       = $data['transfer_co_notes'];
        $tr->co_km          = $data['transfer_co_km'];
        $tr->co_fuel_level  = $data['transfer_co_fuel'];

        $tr->type_id = $data['transferType'];

        $tr->notes =$data['transfer_notes'] ;
        if (isset($data['rental_id'])) {
            $tr->rental_id = $data['rental_id'];
        }
        $tr->save();

        return $tr;
    }

    public function create_transferArray($data)
    {
        $tr = Transition::firstOrNew(['id' => $data['id']]);

        if ($data['driver'] == 'employee') {
            $tr->driver_id = $data['driver_employee_id'];
        } else {
            $tr->driver_id = $data['driver_id'];
        }

        $tr->ci_user_id     = Auth::id();
        $tr->ci_notes       = $data['transfer_ci_notes'];
        $tr->ci_km          = $data['transfer_ci_km'];
        $tr->ci_fuel_level  = $data['transfer_ci_fuel'];
        $tr->ci_datetime    = $data['transfer_ci_datetime'];
        $tr->ci_station_id  = $data['transfer_ci_station'];
        $tr->ci_place_id    = $data['transfer_ci_place_id'];
        $tr->ci_place_text  = $data['transfer_ci_place_text'];
        $tr->vehicle_id     = $data['vehicle_id'];
        $tr->co_user_id     = $data['transfer_co_user_id'];
        $tr->co_datetime    = $data['transfer_co_datetime'];
        $tr->co_station_id  = $data['transfer_co_station'];
        $tr->co_place_id    = $data['transfer_co_place_id'];
        $tr->co_place_text  = $data['transfer_co_place_text'];
        $tr->co_notes       = $data['transfer_co_notes'];
        $tr->co_km          = $data['transfer_co_km'];
        $tr->co_fuel_level  = $data['transfer_co_fuel'];

        $tr->type_id = $data['transferType'];

        $tr->notes = $data['transfer_notes'];
        if (isset($data['rental_id'])) {
            $tr->rental_id = $data['rental_id'];
        }
        $tr->save();

        return $tr;
    }


    public function update_store_api(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'id'            => 'nullable|numeric',
            'type_id'  => 'required',
            'driver'        => 'required',
            // 'driver_id.id'     => 'nullable|exists:drivers,id',
            'driver_id'     => 'nullable',
            //'vehicle_id.id'     => 'required|exists:vehicles,id',
            'vehicle_id'     => 'required',
            'co_datetime' => 'required',
            // 'co_station_id.id'   => 'required|exists:stations,id',
            'co_station_id'   => 'required',
            's_co_place.id'  => 'nullable|exists:places,id',
            'co_km'        => 'required',
            'co_fuel_level'      => 'required',
            //'co_user_id.id'   => 'required|exists:users,id',
            'co_user_id'   => 'required',
            'ci_datetime'  => 'required',
            //'ci_station_id.id'   => 'nullable|exists:stations,id',
            'ci_station_id'   => 'nullable',
            's_ci_place.id'  => 'nullable|exists:places,id'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $tr = Transition::firstOrNew(['id' => $data['id']]);

        if ($data['driver'] == 'employee') { // like role
            if (is_array($data['driver_id'])) {
                $tr->driver_id     = (int)$data['driver_id.id'];
            } else {
                $tr->driver_id     = $data['driver_id'];
            }
        } else {
            if (is_array($data['driver_id'])) {
                $tr->driver_id     = (int)$data['driver_id.id'];
            } else {
                $tr->driver_id     = $data['driver_id'];
            }
        }

        $tr->ci_user_id     = Auth::id();
        $tr->ci_notes       = $data['ci_notes'];
        $tr->ci_km          = $data['ci_km'];
        $tr->ci_fuel_level  = $data['ci_fuel'];
        $tr->ci_datetime    = $data['ci_datetime'];

        if (is_array($data['ci_station_id'])) {
            $tr->ci_station_id  = (int)$data['ci_station_id.id'];
        } else {
            $tr->ci_station_id  = $data['ci_station_id'];
        }

        $tr->ci_place_id    = $data['s_ci_place.id'];
        $tr->ci_place_text  = $data['s_ci_place.name'];
        $tr->ci_fuel_level  = $data['ci_fuel_level'];

        if (is_array($data['ci_user_id'])) {
            $tr->ci_user_id     = (int)$data['ci_user_id.id'];
        } else {
            $tr->ci_user_id     = $data['ci_user_id'];
        }


        if (is_array($data['vehicle_id'])) {
            $tr->vehicle_id     = (int)$data['vehicle_id.id'];
        } else {
            $tr->vehicle_id     = $data['vehicle_id'];
        }



        if (is_array($data['co_user_id'])) {
            $tr->co_user_id     = (int)$data['co_user_id.id'];
        } else {
            $tr->co_user_id     = $data['co_user_id'];
        }

        $tr->co_datetime    = $data['co_datetime'];

        if (is_array($data['co_station_id'])) {
            $tr->co_station_id  = (int)$data['co_station_id.id'];
        } else {
            $tr->co_station_id  = $data['co_station_id'];
        }

        $tr->co_place_id    = $data['s_co_place.id'];
        $tr->co_place_text  = $data['s_co_place.name'];
        $tr->co_notes       = $data['co_notes'];
        $tr->co_km          = $data['co_km'];
        $tr->co_fuel_level  = $data['co_fuel_level'];

        $tr->type_id = $data['type_id'];

        $tr->notes = $data['notes'];
        if (isset($data['rental_id'])) {
            $tr->rental_id = $data['rental_id'];
        }
        $tr->save();
        $tr->documents()->sync($data['documents']);

        return new TransitionResource($tr);
    }
}
