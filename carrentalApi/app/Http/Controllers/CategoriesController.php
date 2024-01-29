<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\CategoryProfile;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $categories = Category::query();
        if ($term) {
            $categories = $categories->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $categories = $categories->paginate(Cookie::get('pages') ?? '5');
        return view('categories.preview', compact(['categories', 'lng']));
    }


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $categories = Category::query();
        if ($term) {
            $categories = $categories->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }
      return new CategoryCollection($categories->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }


    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('categories.create', [
                'lang'     => Language::all(),
                'category' => Category::find($data['cat_id']),
                'lng'      => $lng,
            ]);
        }
        return view('categories.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function update_store_api(Request $data){
        //Step two, create new row for categories db table
        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $category = Category::firstOrNew(['id' => $data['id']]);

        if ($data->hasFile('icon')) {
            $stored_name    = $data->file('icon')->store('public');
            $category->icon = str_replace("public/", "", $stored_name);
        }
        createSlug($data['profiles']['el']['title'], $category);
        $category->save();

        //Step Three, add translations

        foreach ($data['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation              = CategoryProfile::firstOrNew(['category_id' => $category->id, 'language_id' => $lang]);
                $translation->category_id = $category->id;
                $translation->language_id = $lang;
                $translation->title       = $profile['title'];
                $translation->description = $profile['description'];
                $translation->save();
            }
        }

        return  $category;
    }

    public function create(Request $data)
    {
        //Step one, validate required data for categories db table
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Η κατηγορία ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Η κατηγορία δημιουργήθηκε με επιτυχία.'));
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
        $category = Category::firstOrNew(['id' => $data['id']]);

        if ($data->hasFile('icon')) {
            $stored_name    = $data->file('icon')->store('public');
            $category->icon = str_replace("public/", "", $stored_name);
        }
        createSlug($data['title'][config('app.locale')], $category);
        $category->save();

        //Step Three, add translations

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation              = CategoryProfile::firstOrNew(['category_id' => $category->id, 'language_id' => $lang]);
                $translation->category_id = $category->id;
                $translation->language_id = $lang;
                $translation->title       = $data['title'][$lang];
                $translation->description = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }

    public function edit(Request $request, $id) {
        $category = Category::find($id);

        return new CategoryResource($category);
    }

    public function upload(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'file' => 'nullable|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $category = Category::find($data['id']);
        if ($data->hasFile('file')) {
            $stored_name = $data->file('file')->store('public');
            $category->icon  = str_replace("public/", "", $stored_name);
            $category->save();
        }

        return new CategoryResource($category);
    }

    public function uploadRemove(Request $data, $id)
    {
        $category = Category::find($id);
        Storage::delete('public/' . $category->icon);
        $category->icon = null;
        $category->save();
        return new CategoryResource($category);
    }

    public function delete(Request $data)
    {
        $users = Category::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function delete_api(Request $data)
    {
            $cat = Category::find($data['id']);
            $cat->delete();
            return new CategoryResource($cat);
    }

    public function delete_icon(Request $data){
        $cat = Category::find($data['id']);
        $cat->icon = null;
        $cat->save();
    }

}
