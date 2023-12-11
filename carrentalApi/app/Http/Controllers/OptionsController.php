<?php

namespace App\Http\Controllers;

use App\Http\Resources\OptionCollection;
use App\Http\Resources\OptionResource;
use Illuminate\Http\Request;
use App\Option;
use App\OptionProfile;
use App\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview(Request $data, $lng, $option_type)
    {
        $term = $data['search'];
        $options = Option::where('option_type', $option_type);

        if ($term) {
            $options = $options->where('option_type', $option_type)->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $options = $options->orderBy('order')->paginate(Cookie::get('pages') ?? '5');
        return view('options.preview', compact(['options', 'lng', 'option_type']));
    }


    public function preview_api(Request $request, $option_type, $lng = null)
    {
        $term = $request['search'];
        $options = Option::query();

        if ($option_type) {
            $options = $options->where('option_type', $option_type);
        }

        if ($term) {
            $options = $options->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

       return new OptionCollection($options->orderBy('order')->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }



    public function search_ajax(Request $data, $lng, $option_type)
    {
        $mystring = $data['search'];
        $options  = Option::where('option_type', $option_type)->where(function($q) use ($mystring, $lng) {
                        $q->where('slug', 'like', "%" . $mystring . "%")
                          ->orWhereHas("profiles", function ($sub_q) use ($mystring, $lng) {
                              $sub_q->where("title", "like", "%" . $mystring . "%");
                              $sub_q->where('language_id', $lng);
                          });
                    })->take(Cookie::get('pages') ?? '5')->get();
        $options->each->append('profile_title');
        return response()->json($options);
    }

    public function create_view(Request $data, $lng, $option_type)
    {
        if (isset($data['cat_id'])) {
            return view('options.create', [
                'lang'   => Language::all(),
                'option' => Option::where('option_type', $option_type)->find($data['cat_id']),
                'lng'    => $lng,
                'option_type' => $option_type
            ]);
        }
        return view('options.create', ['lang' => Language::all(), 'lng'=>$lng, 'option_type' => $option_type]);
    }

    public function delete(Request $data)
    {
        $option = Option::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function delete_api(Request $data)
    {
            $option = Option::find($data['id']);// v2 sends one by one
            $option->delete();
            return new OptionResource($option);
    }

    public function edit(Request $request,$option_type,$id) {
        $option = Option::find($id);

        return new OptionResource($option);
    }

    public function upload(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'file'              => 'nullable|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $option = Option::find($data['id']);
        if ($data->hasFile('file')) {
            $stored_name = $data->file('file')->store('public');
            $option->icon  = str_replace("public/", "", $stored_name);
            $option->save();
        }

        return new OptionResource($option);
    }

    public function uploadRemove(Request $data,$option_type,$id)
    {
        $option = Option::find($id);
        Storage::delete('public/' . $option->icon);
        $option->icon = null;
        $option->save();
         return new OptionResource($option);
    }

    public function update_store_api(Request $data,$option_type){
        $validator = Validator::make($data->all(), [
            'id'                => 'nullable|numeric',
           // 'icon'              => 'nullable|mimes:jpeg,png,jpg',
            'cost_max'         => 'nullable|numeric',
            'items_max'         => 'nullable|numeric',
            'active_daily_cost' => 'nullable',
            'cost_daily'        => 'nullable|numeric',
            'default_on'        => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        //Step two, create new row for categories db table
        $option = Option::firstOrNew(['id' => $data['id']]);

        if ($data->hasFile('icon')) {
            $stored_name  = $data->file('icon')->store('public');
            $option->icon = str_replace("public/", "", $stored_name);
        }
        $option->cost_daily        = $data['cost_daily'];
        $option->cost_max          = $data['cost_max'];
        $option->items_max         = $data['items_max'];
        $option->active_daily_cost = ($data['active_daily_cost'] == 'on') ? TRUE : NULL;
        $option->default_on        = ($data['default_on'] == 'on') ? TRUE : NULL;
        $option->option_type       = $option_type;
        createSlug($data['profiles']['el']['title'], $option);
        $option->order             = $data['order'];
        $option->code              = $data['code'];
        $option->save();

        //Step Three, add translations
        foreach ($data['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation              = OptionProfile::firstOrNew(['option_id' => $option->id, 'language_id' => $lang]);
                $translation->option_id   = $option->id;
                $translation->language_id = $lang;
                $translation->title       = $profile['title'];
                $translation->description = $profile['description'];
                $translation->save();
            }
        }
        return new OptionResource($option);
    }

    public function create(Request $data, $lng, $option_type)
    {
        //Step one, validate required data for categories db table
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Το option ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Το option δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'                => 'nullable|numeric',
            'icon'              => 'nullable|mimes:jpeg,png,jpg',
            'max_price'         => 'nullable|numeric',
            'items_max'         => 'nullable|numeric',
            'active_daily_cost' => 'nullable',
            'cost_daily'        => 'nullable|numeric',
            'default_on'        => 'nullable'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        //Step two, create new row for categories db table
        $option = Option::firstOrNew(['id' => $data['id']]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        if ($data->hasFile('icon')) {
            $stored_name  = $data->file('icon')->store('public');
            $option->icon = str_replace("public/", "", $stored_name);
        }
        $option->cost_daily        = $data['cost_daily'];
        $option->cost_max          = $data['cost_max'];
        $option->items_max         = $data['items_max'];
        $option->active_daily_cost = ($data['active_daily_cost'] == 'on') ? TRUE : NULL;
        $option->default_on        = ($data['default_on'] == 'on') ? TRUE : NULL;
        $option->option_type       = $option_type;
        createSlug($data['title'][config('app.locale')], $option);
        $option->order             = $data['order'];
        $option->code              = $data['code'];
        $option->save();

        //Step Three, add translations
        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation              = OptionProfile::firstOrNew(['option_id' => $option->id, 'language_id' => $lang]);
                $translation->option_id   = $option->id;
                $translation->language_id = $lang;
                $translation->title       = $data['title'][$lang];
                $translation->description = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }

    public function delete_icon(Request $data){
        $cat = Option::find($data['id']);
        $cat->icon = null;
        $cat->save();
    }
}