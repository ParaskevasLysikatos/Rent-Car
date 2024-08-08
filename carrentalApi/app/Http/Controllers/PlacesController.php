<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaceCollection;
use App\Http\Resources\PlaceResource;
use App\Models\Language;
use App\Models\Place;
use App\Models\PlaceProfile;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PlacesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $places = Place::query();

        if ($term) {
            $places = $places->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $places = Place::paginate(Cookie::get('pages') ?? '5');
        return view('places.preview', compact(['places', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $term = $request['search'];
        $places = Place::query()->orderBy('created_at', 'desc');

        if ($term) {
            $places = $places->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

     return new PlaceCollection($places->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));

    }

    public function edit(Request $request, $id) {
        $station = Place::find($id);

        return new PlaceResource($station);
    }

    public function populate(Request $request)
    {
        $lng = Lang::locale();
        $station_id = $request->get('station_id');
        $places = PlaceProfile::where('language_id', $lng)->whereHas('place', function ($q) use ($station_id) {
            $q->whereHas('stations', function ($sub_q) use ($station_id) {
                $sub_q->where('stations.id', $station_id);
            });
        })->take(Cookie::get('pages') ?? '5')->get(['place_id', 'title']);
        return response()->json($places);
    }

    public function search_ajax(Request $data, $lng) {
        $mystring = $data['search'];
        $station = $data['stations'];

        $places = Place::where(function ($q) use ($mystring, $lng) {
            $q->where('slug', 'like', "%" . $mystring . "%")
                ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                    $q->where("title", "like", "%" . $mystring . "%");
                    $q->where('language_id', $lng);
                });
        });

        if ($station) {
            $places = $places->whereHas('stations', function ($q) use ($station) {
                $q->where('stations.id', $station);
            });
        }

        $places = $places->with('stations')->take(Cookie::get('pages') ?? '5')->get();
        $places->each->append('profile_title');
        return response()->json($places);
    }

    public function create_view(Request $data, $lng)
    {
        $stations = Station::all();
        if (isset($data['cat_id'])) {
            return view('places.create', [
                'lang'            => Language::all(),
                'place'         => Place::find($data['cat_id']),
                'lng'             => $lng,
                'stations' => $stations
            ]);
        }
        return view('places.create', ['lang' => Language::all(), 'lng'=>$lng, 'stations' => $stations]);
    }

    public function delete_api(Request $request)
    {
            $place = Place::find($request['id']);// v2 sends one by one
            $place->delete();
            return new PlaceResource($place);
    }

    public function delete(Request $data)
    {
        $place = Place::whereIn('id', $data['ids'])->delete();
        return "ok";
    }


    public function updateFromRequest(Request $request, Place $place) {
        $validator = Validator::make($request->all(), [
            'id'         => 'nullable|numeric',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'stations'   => 'required|exists:stations,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $place->latitude      = $request['latitude'];
        $place->longitude     = $request['longitude'];

        createSlug($request->profiles[config('app.locale')]['title'], $place);
        $place->save();

        //Handle stations
        $currentStations = $place->stations()->pluck('stations.id')->toArray();
        $stationsToAdd = array_diff($request['stations'], $currentStations);
        $stationsToRemove = array_diff($currentStations, $request['stations']);
        foreach ($stationsToAdd as $stationToAdd) {
            $place->stations()->attach($stationToAdd);
        }
        foreach ($stationsToRemove as $stationToRemove) {
            $place->stations()->detach($stationToRemove);
        }

        foreach ($request['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation                    = PlaceProfile::firstOrNew(['place_id' => $place->id, 'language_id' => $lang]);
                $translation->place_id          = $place->id;
                $translation->language_id       = $lang;
                $translation->title             = $profile['title'];
                $translation->description       = $profile['description'];
                $translation->save();
            }
        }

        return $place;
    }

    public function store(Request $request) {
        $place = new Place();
        $this->updateFromRequest($request, $place);

        return new PlaceResource($place);
    }

    public function update(Request $request, $id) {
        $place = place::findOrFail($id);
        $this->updateFromRequest($request, $place);

        return new PlaceResource($place);
    }

    public function create(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'         => 'nullable|numeric',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'stations'   => 'required|exists:stations,id'
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }


//        return "<pre>".print_r($data->all(), 1)."</pre>";
        $place                = Place::firstOrNew(['id' => $data['id']]);
        $place->latitude      = $data['latitude'];
        $place->longitude     = $data['longitude'];

        createSlug($data['title'][config('app.locale')], $place);
        $place->save();

        //Handle stations
        $currentStations = $place->stations()->pluck('stations.id')->toArray();
        $stationsToAdd = array_diff($data['stations'], $currentStations);
        $stationsToRemove = array_diff($currentStations, $data['stations']);
        foreach ($stationsToAdd as $stationToAdd) {
            $place->stations()->attach($stationToAdd);
        }
        foreach ($stationsToRemove as $stationToRemove) {
            $place->stations()->detach($stationToRemove);
        }

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = PlaceProfile::firstOrNew(['place_id' => $place->id, 'language_id' => $lang]);
                $translation->place_id          = $place->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->description       = $data['description'][$lang];
                $translation->save();
            }
        }

        if ($data->ajax()) {
            return response()->json($place->append('profile_title'));
        }

        return redirect()->back();
    }
}
