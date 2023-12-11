<?php

namespace App\Http\Controllers;

use App\Http\Requests\StationRequest;
use App\Http\Resources\StationCollection;
use App\Http\Resources\StationResource;
use App\Language;
use App\Location;
use App\Station;
use App\StationProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;

class StationsController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $stations = Station::query();

        if ($term) {
            $stations = $stations->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $stations = Station::paginate(Cookie::get('pages') ?? '5');
        return view('stations.preview', compact(['stations', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $term = $request['search'];
        $stations = Station::query()->orderBy('created_at', 'desc');

        if ($term) {
            $stations = $stations->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $stations = $stations->filter($request);
        return new StationCollection($stations->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));

    }

    public function edit(Request $request, $id)
    {
        $station = Station::find($id);

        return new StationResource($station);
    }

    public function updateFromRequest(Request $request, Station $station)
    {
        $validator = Validator::make($request->all(), [
            'id'         => 'nullable|numeric',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
           //'location_id.id'   => 'required|exists:locations,id'
            'location_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $station->address       = $request['address'];
        $station->city          = $request['city'];
        $station->country       = $request['country'];
        $station->zip_code      = $request['zip_code'];
        $station->phone         = $request['phone'];
        $station->code          = $request['code'];

        if (is_array($request['location_id'])) {
            $station->location_id   = (int)$request['location_id.id'];
        } else {
            $station->location_id   = $request['location_id'];
        }

        $station->location_id   = $request['location_id'];
        $station->latitude      = $request['latitude'];
        $station->longitude     = $request['longitude'];
        createSlug($request['profiles']['el']['title'], $station);
        $station->save();

        foreach ($request['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation                    = StationProfile::firstOrNew(['station_id' => $station->id, 'language_id' => $lang]);
                $translation->station_id        = $station->id;
                $translation->language_id       = $lang;
                $translation->title             = $profile['title'];
                $translation->description       = $profile['description'];
                $translation->save();
            }
        }

        return $station;
    }

    public function store_api(StationRequest $request)
    {
        $station = new Station();
        $this->updateFromRequest($request, $station);

        return new StationResource($station);
    }

    public function update(StationRequest $request, $id)
    {
        $station = Station::findOrFail($id);
        $this->updateFromRequest($request, $station);

        return new StationResource($station);
    }

    public function search_ajax(Request $data, $lng)
    {
        $mystring = $data['search'];

        $stations = Station::select('id')->where('id', $mystring)->orWhere('slug', 'like', "%" . $mystring . "%")
            ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                $q->where("title", "like", "%" . $mystring . "%");
                $q->where('language_id', $lng);
            })
            ->take(Cookie::get('pages') ?? '5')->get();
        $stations->each->append('profile_title');
        return response()->json($stations);
    }

    public function create_view(Request $data, $lng)
    {
        $locations = Location::all();
        if (isset($data['cat_id'])) {
            return view('stations.create', [
                'lang'            => Language::all(),
                'station'         => Station::find($data['cat_id']),
                'lng'             => $lng,
                'locations' => $locations
            ]);
        }
        return view('stations.create', ['lang' => Language::all(), 'lng' => $lng, 'locations' => $locations]);
    }

    public function delete_api(Request $data)
    {
            $station = Station::find($data['id']);// v2 sends one by one
            $station->delete();
            return new StationResource($station);
    }


    public function delete(Request $data)
    {
        $station = Station::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function store(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'         => 'nullable|numeric',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'location'   => 'required|exists:locations,id'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }


        //        return "<pre>".print_r($data->all(), 1)."</pre>";

        $station                = Station::firstOrNew(['id' => $data['id']]);
        $station->address       = $data['address'];
        $station->city          = $data['city'];
        $station->country       = $data['country'];
        $station->zip_code      = $data['zip_code'];
        $station->phone         = $data['phone'];
        $station->code          = $data['code'];
        $station->location_id   = $data['location'];
        $station->latitude      = $data['latitude'];
        $station->longitude     = $data['longitude'];
        $station->aade_branch   = $data['aade_branch'];
        createSlug($data['title'][config('app.locale')], $station);
        $station->save();

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = StationProfile::firstOrNew(['station_id' => $station->id, 'language_id' => $lang]);
                $translation->station_id        = $station->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->description       = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }
}