<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Language;
use App\Models\Location;
use App\Models\LocationProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LocationsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }


    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $locations = Location::query();

        if ($term) {
            $locations = $locations->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $locations = $locations->paginate(Cookie::get('pages') ?? '5');
        return view('locations.preview', compact(['locations', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $term = $request['search'];
        $locations = Location::query()->orderBy('created_at', 'desc');

        if ($term) {
            $locations = $locations->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    if ($lng) {
                        $q->where('language_id', $lng);
                    }
                });
        }

     return new  LocationCollection($locations->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function edit(Request $request, $id) {
        $location = Location::find($id);

        return new LocationResource($location);
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('locations.create', [
                'lang'   => Language::all(),
                'location' => Location::find($data['cat_id']),
                'lng'    => $lng,
            ]);
        }
        return view('locations.create', ['lang' => Language::all(), 'lng'=>$lng]);
    }

    public function delete(Request $data)
    {
        $location = Location::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function delete_api(Request $request)
    {
            $location=Location::find($request['id']);// v2 sends one by one
            $location->delete();
            return new LocationResource($location);
    }

    public function updateFromRequest(Request $request, Location $location) {
        $validator = Validator::make($request->all(), [
            'id'   => 'nullable|numeric',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $location->latitude      = $request['latitude'];
        $location->longitude     = $request['longitude'];
        createSlug($request['profiles']['el']['title'], $location);
        $location->save();

        foreach ($request['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation                    = LocationProfile::firstOrNew(['location_id' => $location->id, 'language_id' => $lang]);
                $translation->location_id       = $location->id;
                $translation->language_id       = $lang;
                $translation->title             = $profile['title'];
                $translation->save();
            }
        }

        return $location;
    }

    public function store(Request $request) {
        $location = new Location();
        $location = $this->updateFromRequest($request, $location);
        return new LocationResource($location);
    }

    public function update(Request $request, $id) {
        $location = Location::find($id);
        $location = $this->updateFromRequest($request, $location);
        return new LocationResource($location);
    }

    public function create(Request $data)
    {

        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|numeric',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $location            = Location::firstOrNew(['id' => $data['id']]);
        $location->latitude  = $data['latitude'];
        $location->longitude = $data['longitude'];

        createSlug($data['title'][config('app.locale')], $location);
        $location->save();

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = LocationProfile::firstOrNew(['location_id' => $location->id, 'language_id' => $lang]);
                $translation->location_id       = $location->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }

}
