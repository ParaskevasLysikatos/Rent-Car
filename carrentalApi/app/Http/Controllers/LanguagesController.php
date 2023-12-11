<?php

namespace App\Http\Controllers;

use App\Http\Resources\LanguagesCollection;
use App\Http\Resources\LanguagesResource;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Illuminate\Support\Facades\Session;

class LanguagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $languages = Language::query();

        if ($term) {
            $languages = $languages->where('id', 'like', "%" . $term . "%")
                ->orwhere("title", "like", "%" . $term . "%");
        }

        $languages = $languages->orderBy('order')->paginate(Cookie::get('pages') ?? '5');
        return view('languages.preview', compact(['languages', 'lng']));
    }


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $languages = Language::query()->orderBy('id', 'desc');

        if ($term) {
            $languages = $languages->where('id', 'like', "%" . $term . "%")
                ->orwhere("title", "like", "%" . $term . "%");
        }
            return new LanguagesCollection($languages->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }



    public function create_view(Request $data, $lng)
    {
        $exist_languages = Language::all();
        if (isset($data['cat_id'])) {
            return view('languages.create', [
                'language' => Language::find($data['cat_id']),
                'exist_languages' => $exist_languages,
                'lng'            => $lng
            ]);
        }
        return view('languages.create', ['lng', 'exist_languages']);
    }

     public function edit(Request $request, $id) {
        $language = Language::find($id);

        return new LanguagesResource($language);
    }


    public function delete(Request $data)
    {
        $option = Language::whereIn('id', $data['ids'])->delete();
        return "ok";
    }


    public function delete_api(Request $data)
    {
            $language = Language::find($data['id']);// v2 sends one by one
            $language->delete();
            return new LanguagesResource($language);
    }

    public function create(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Η γλώσσα ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Η γλώσσα δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'language' => 'required|unique:languages,id,' . $data['id'],
            'title' => 'required',
            'title_international' => 'nullable'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $language = Language::firstOrNew(['id' => $data['id']]);

        $language->id = $data['language'];
        $language->title = $data['title'];
        $language->title_international = $data['title_international'];
        $language->save();

        $language::where('id', '')->delete();

        return redirect()->back();
    }


    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'title' => 'required',
            'title_international' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $language = Language::firstOrNew(['id' => $data['id']]);

        $language->id = $data['id'];
        $language->title = $data['title'];
        $language->title_international = $data['title_international'];
        $language->save();
        return new LanguagesResource($language);
    }

    public function order(Request $data){
        foreach ($data['ids'] as $index => $value){
            Language::where('id', $value)->update(['order' => $index]);
        }
        return $data->all();
    }

}
