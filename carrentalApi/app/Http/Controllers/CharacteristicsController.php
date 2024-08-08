<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CharacteristicsCollection;
use App\Http\Resources\CharacteristicsResource;
use App\Models\Characteristic;
use App\Models\CharacteristicProfile;
use App\Models\Language;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CharacteristicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $characteristics = Characteristic::query();

        if ($term) {
            $characteristics = $characteristics->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        if ($data->ajax()) {
            $characteristics = $characteristics->take(Cookie::get('pages') ?? '5')->get(['id']);
            $characteristics->each->append('profile_title');
            return response()->json($characteristics);
        } else {
            $characteristics = $characteristics->paginate(Cookie::get('pages') ?? '5');
            return view('characteristics.preview', compact(['characteristics', 'lng']));
        }
    }


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $characteristics = Characteristic::query()->orderBy('created_at', 'desc');
        if ($term) {
            $characteristics = $characteristics->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }
     return new CharacteristicsCollection($characteristics->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }



    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('characteristics.create', [
                'lang'           => Language::all(),
                'characteristic' => Characteristic::find($data['cat_id']),
                'lng'            => $lng
            ]);
        }
        return view('characteristics.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function delete(Request $data)
    {
        $users = Characteristic::whereIn('id', $data['ids'])->delete();
        return "Characteristic deleted";
    }

    public function delete_api(Request $data)
    {
            $char = Characteristic::find($data['id']);// v2 sends one by one del requests
            $char->delete();
            return new CharacteristicsResource($char);
    }

    public function edit(Request $request, $id) {
        $characteristics = Characteristic::find($id);

        return new CharacteristicsResource($characteristics);
    }

    public function upload(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'file' => 'nullable|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $characteristic = Characteristic::find($data['id']);
        if ($data->hasFile('file')) {
            $stored_name = $data->file('file')->store('public');
            $characteristic->icon  = str_replace("public/", "", $stored_name);
            $characteristic->save();
        }

        return new CharacteristicsResource($characteristic);
    }

    public function uploadRemove(Request $data, $id)
    {
        $characteristic = Characteristic::find($id);
        Storage::delete('public/' . $characteristic->icon);
        $characteristic->icon = null;
        $characteristic->save();
        return new CharacteristicsResource($characteristic);
    }

    public function update_store_api(Request $data){
        //Step two, create new row for categories db table
        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $characteristic = Characteristic::firstOrNew(['id' => $data['id']]);

        if ($data->hasFile('icon')) {
            $stored_name          = $data->file('icon')->store('public');
            $characteristic->icon = str_replace("public/", "", $stored_name);
        }
        createSlug($data['profiles']['el']['title'], $characteristic);
        $characteristic->save();

        //Step Three, add translations
        foreach ($data['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation                    = CharacteristicProfile::firstOrNew(['characteristic_id' => $characteristic->id, 'language_id' => $lang]);
                $translation->characteristic_id = $characteristic->id;
                $translation->language_id       = $lang;
                $translation->title             = $profile['title'];
                $translation->description       = $profile['description'];
                $translation->save();
            }
        }

        return new CharacteristicsResource($characteristic);
    }

    public function create(Request $data)
    {
        //Step one, validate required data for categories db table
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Το χαρακτηριστικό ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Το χαρακτηριστικό δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|numeric',
            'icon' => 'nullable|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        //Step two, create new row for categories db table
        $characteristic = Characteristic::firstOrNew(['id' => $data['id']]);

        if ($data->hasFile('icon')) {
            $stored_name          = $data->file('icon')->store('public');
            $characteristic->icon = str_replace("public/", "", $stored_name);
        }
        createSlug($data['title'][config('app.locale')], $characteristic);
        $characteristic->save();

        //Step Three, add translations
        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = CharacteristicProfile::firstOrNew(['characteristic_id' => $characteristic->id, 'language_id' => $lang]);
                $translation->characteristic_id = $characteristic->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->description       = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }
    public function delete_icon(Request $data){
        $cat = Characteristic::find($data['id']);
        $cat->icon = null;
        $cat->save();
    }
}
