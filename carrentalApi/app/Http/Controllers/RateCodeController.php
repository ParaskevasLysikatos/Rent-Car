<?php

namespace App\Http\Controllers;

use App\Http\Resources\RateCodesCollection;
use App\Http\Resources\RateCodesResource;
use App\Models\Language;
use App\Models\RateCode;
use App\Models\RateCodesProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RateCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview($lng)
    {
        $rate_codes = RateCode::paginate(Cookie::get('pages') ?? '5');
        return view('rate_codes.preview', compact(['rate_codes', 'lng']));
    }


    public function preview_api(Request $request,$lng=null)
    {
         $rate_codes = RateCode::query()->orderBy('created_at', 'desc');
        return new RateCodesCollection($rate_codes->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

       public function edit(Request $request, $id) {
        $rate_codes = RateCode::find($id);

        return new RateCodesResource($rate_codes);
        }

    public function search(Request $data, $lng)
    {
        $mystring = $data['search'];
        $rate_codes  = RateCode::where('slug', 'like', "%" . $mystring . "%")
            ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                $q->where("title", "like", "%" . $mystring . "%");
                $q->where('language_id', $lng);
            })->paginate(Cookie::get('pages') ?? '5');
        return view('rate_codes.preview', compact(['rate_codes', 'lng']));
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('rate_codes.create', [
                'lang'            => Language::all(),
                'rate_code'         => RateCode::find($data['cat_id']),
                'lng'             => $lng
            ]);
        }
        return view('rate_codes.create', ['lang' => Language::all(), 'lng'=>$lng]);
    }

    public function delete(Request $data)
    {
        $rate_code = RateCode::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function delete_api(Request $data)
    {
            $rate_code = RateCode::find($data['id']);// v2 sends one by one
            $rate_code->delete();
            return new RateCodesResource($rate_code);
    }

    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'id'         => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
          $rate_code                = RateCode::firstOrNew(['id' => $data['id']]);
        createSlug($data['profiles']['el']['title'], $rate_code);
        $rate_code->save();

        foreach ($data['profiles'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = RateCodesProfile::firstOrNew(['rate_code_id' => $rate_code->id, 'language_id' => $lang]);
                $translation->rate_code_id      = $rate_code->id;
                $translation->language_id       = $lang;
                $translation->title             = $value['title'];
                $translation->description       = $value['description'];
                $translation->save();
            }
        }

        return new RateCodesResource($rate_code);
    }

    public function create(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'         => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }


//        return "<pre>".print_r($data->all(), 1)."</pre>";

        $rate_code                = RateCode::firstOrNew(['id' => $data['id']]);
        createSlug($data['title'][config('app.locale')], $rate_code);
        $rate_code->save();

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = RateCodesProfile::firstOrNew(['rate_code_id' => $rate_code->id, 'language_id' => $lang]);
                $translation->rate_code_id      = $rate_code->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->description       = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }
}
