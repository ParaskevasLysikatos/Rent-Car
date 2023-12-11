<?php

namespace App\Http\Controllers;

use App\Insurance;
use App\InsurancesProfile;
use App\Language;
use Cookie;
use Illuminate\Http\Request;
use Session;
use Validator;

class InsuranceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $insurances = Insurance::query();

        if ($term) {
            $insurances->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $insurances = $insurances->paginate(Cookie::get('pages') ?? '5');
        return view('insurances.preview', compact(['insurances', 'lng']));
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('insurances.create', [
                'lang'            => Language::all(),
                'insurance'         => Insurance::find($data['cat_id']),
                'lng'             => $lng
            ]);
        }
        return view('insurances.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function delete(Request $data)
    {
        $insurance = Insurance::whereIn('id', $data['ids'])->delete();
        return "ok";
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

        $insurance                = Insurance::firstOrNew(['id' => $data['id']]);
        createSlug($data['title'][config('app.locale')], $insurance);
        $insurance->save();

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = InsurancesProfile::firstOrNew(['insurance_id' => $insurance->id, 'language_id' => $lang]);
                $translation->insurance_id      = $insurance->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->description       = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }
}