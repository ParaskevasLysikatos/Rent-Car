<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceDetailsCollection;
use App\Http\Resources\ServiceVisitCollection;
use App\Http\Resources\ServiceVisitResource;
use App\Models\ServiceVisit;
use App\Models\Vehicle;
use App\ServiceDetails;
use App\ServiceStatus;
use App\ServiceVisitDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview_api(Request $data){
        $term = $data['search'];
        $visits = ServiceVisit::query();

        if ($term) {
            $visits = $visits->where('date_start', 'like', "%" . $term . "%")
                ->orwhere('km', 'like', "%" . $term . "%")
                ->orWhereHas('vehicle', function (Builder $q) use ($term) {
                    $q->whereHas('license_plates', function (Builder $license_q) use ($term) {
                        $license_q->where('licence_plate', 'like', "%" . $term . "%");
                    });
                });
        }

        return new ServiceVisitCollection($visits->applyOrder($data)->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page')); //->with('visit_details','licence_plate','vehicle')

    }

    public function service_details(Request $request) {
        $service_details=ServiceDetails::query()->orderBy('created_at', 'desc');

        return new ServiceDetailsCollection($service_details->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }


    public function service_status(Request $request) {
        $service_status =ServiceStatus::get();
        return $service_status;
    }


    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $visits = ServiceVisit::query();

        if ($term) {
            $visits = $visits->where('date_start', 'like', "%" . $term . "%")
                ->orwhere('km', 'like', "%" . $term . "%")
                ->orWhereHas('vehicle', function (Builder $q) use ($term) {
                    $q->whereHas('license_plates', function (Builder $license_q) use ($term) {
                        $license_q->where('licence_plate', 'like', "%" . $term . "%");
                    });
                });
        }

        $details    = ServiceDetails::select('id', 'title')->where('category', 'general')->orderBy('order', 'asc')->orderBy('category')->get();
        $status     = ServiceStatus::all();
        $visits = $visits->orderBy('created_at', 'desc')->with('visit_details')->paginate(Cookie::get('pages') ?? '5');
        return view('visits.preview', compact(['visits', 'status', 'details', 'lng']));
    }


    public function edit(Request $request, $id) {
        $visit = ServiceVisit::find($id);
        return new ServiceVisitResource($visit);
    }



    public function create_view(Request $data, $lng)
    {
        $details    = ServiceDetails::orderBy('order', 'asc')->orderBy('category')->get();
        $status     = ServiceStatus::all();
        if (isset($data['cat_id'])) {
            return view('visits.create', [
                'visit'         => ServiceVisit::find($data['cat_id']),
                'lng'           => $lng,
                'details'       =>$details,
                'status'        =>$status,
            ]);
        }

        if(isset($data['car'])){
            $car = Vehicle::find($data['car']);
            if ($car === null) {
               return redirect()->route('cars', $lng);
            }else{
                $old_data = ServiceVisit::where('vehicle_id', $car->id)->orderBy('created_at', 'desc')->first();
                return view('visits.create', [
                    'lng'           =>  $lng,
                    'car'           =>  $car,
                    'details'       =>$details,
                    'status'        =>$status,
                    'old_data'      =>$old_data
                ]);
            }
        }

        return redirect()->route('cars', $lng);
    }

    public function delete_api(Request $data)
    {
        if(Auth::user()->role->id=='service'){
            $user = ServiceVisit::whereIn('id', $data['ids'])->where('user_id', Auth::id())->delete();

                $visit = ServiceVisit::find($data['id']);
                $visit->delete();
                return new ServiceVisitResource($visit);
        }else{
            $user = ServiceVisit::whereIn('id', $data['ids'])->delete();

            $visit = ServiceVisit::find($data['id']);
            $visit->delete();
            return new ServiceVisitResource($visit);
        }
    }

    public function delete(Request $data)
    {
        if (Auth::user()->role->id == 'service')
        $user = ServiceVisit::whereIn('id', $data['ids'])->where('user_id', Auth::id())->delete();
        else
            $user = ServiceVisit::whereIn('id', $data['ids'])->delete();

        return "ok";
    }

      public function update_api(Request $data, $lng){
        //Step two, create new row for categories db table
        $validator = Validator::make($data->all(), [
            'id'                => 'nullable|unique:service_visit,id,' . $data['id'],
            'date'              => 'required',
            'km'                => 'required',
            'fuel_level'        => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $visit = ServiceVisit::firstOrNew(['id' => $data['id']]);
        $visit->user_id    = Auth::id();
        $visit->date_start = $data['date'];
        $visit->vehicle_id = $data['vehicle_id'];
        $visit->km         = $data['km'];
        $visit->fuel_level = $data['fuel_level'];
        $visit->comments   = $data['comments'];
        $visit->save();

        $visitDetails = ServiceVisitDetails::where('service_visit_id', $visit->id)->delete();

        if($data['visit_details']){
             $statusD='';
            $statusS='';
            foreach ($data['visit_details'] as $detail => $statuses){
            echo "<pre>".print_r($statuses, 1)."</pre>";
            foreach($statuses as $status => $val){
                 echo "<pre> status: ".print_r($status, 1)."</pre>";
                echo "<pre> val: ".print_r($val, 1)."</pre>";
                if($status=='service_details_id'){
                     $statusD=$val;
                }
                  if($status=='service_status_id'){
                     $statusS=$val;
                }

            }
            ServiceVisitDetails::create([
                    'service_visit_id'      => $visit->id,
                    'service_details_id'    => $statusD,
                    'service_status_id'     => $statusS
                ]);
            }
        }


         return new ServiceVisitResource($visit);
      }



    public function create(Request $data, $lng)
    {

        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'                => 'nullable|unique:service_visit,id,' . $data['id'],
            'date'              => 'required',
            'km'                => 'required',
            'fuel_level'        => 'required'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

//        return "<pre>".print_r($data->all(), 1)."</pre>";

        //Step two, create new row for categories db table
        $visit = ServiceVisit::firstOrNew(['id' => $data['id']]);
        $visit->user_id    = Auth::id();
        $visit->date_start = $data['date'];
        $visit->vehicle_id = $data['car_id'];
        $visit->km         = $data['km'];
        $visit->fuel_level = $data['fuel_level'];
        $visit->comments   = $data['comments'];
        $visit->save();

        $visitDetails = ServiceVisitDetails::where('service_visit_id', $visit->id)->delete();

        if($data['checked'])
        foreach ($data['checked'] as $detail => $statuses){
            echo "<pre>".print_r($statuses, 1)."</pre>";
            foreach($statuses as $status => $val){
                echo "<pre> status: ".print_r($status, 1)."</pre>";
                ServiceVisitDetails::create([
                    'service_visit_id'      => $visit->id,
                    'service_details_id'    => $detail,
                    'service_status_id'     => $status
                ]);
            }
        }

        return redirect()->route('visits', $lng);
    }
}
