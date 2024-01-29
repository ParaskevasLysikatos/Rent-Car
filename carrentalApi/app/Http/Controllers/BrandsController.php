<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BrandCollection;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\BrandProfile;
use App\Models\Language;
use App\Models\PrintingFormsColor;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BrandsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview(Request $data, $lng)
    {
        $term = $data['search'];

        $brands = Brand::query();
        if ($term) {
            $brands = $brands->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $brands = $brands->paginate(Cookie::get('pages') ?? '5');
        return view('brands.preview', compact(['brands', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $term = $request['search'];

        $brands = Brand::query()->orderBy('created_at', 'desc');;
        if ($term) {
            $brands = $brands->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }
        return new BrandCollection($brands->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function edit(Request $request, $id) {
        $brand = Brand::find($id);
        return new BrandResource($brand);
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('brands.create', [
                'lang'           => Language::all(),
                'brand' => Brand::find($data['cat_id']),
                'lng'            => $lng
            ]);
        }
        return view('brands.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function delete(Request $data)
    {
        $brands = Brand::whereIn('id', $data['ids'])->delete();
        return "Brand deleted";
    }

    public function delete_api(Request $data)
    {
            $brands = Brand::find($data['id']); // v2 sends one by one del requests
            $brands->delete();
            return new BrandResource($brands);
    }

    public function upload(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'file' => 'nullable|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $brands = Brand::find($data['id']);
        if ($data->hasFile('file')) {
            $stored_name = $data->file('file')->store('public');
            $brands->icon  = str_replace("public/", "", $stored_name);
            $brands->save();
        }

        return new BrandResource($brands);
    }

    public function uploadRemove(Request $data, $id)
    {
        $brands = Brand::find($id);
        Storage::delete('public/' . $brands->icon);
        $brands->icon = null;
        $brands->save();
        return new BrandResource($brands);
    }


    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $Brand = Brand::firstOrNew(['id' => $data['id']]);

        if ($data->hasFile('icon')) {
            $stored_name          = $data->file('icon')->store('public');
            $Brand->icon = str_replace("public/", "", $stored_name);
        }
        createSlug($data['profiles']['el']['title'], $Brand);
        $Brand->save();

        //Step Three, add translations
        foreach ($data['profiles'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = BrandProfile::firstOrNew(['brand_id' => $Brand->id, 'language_id' => $lang]);
                $translation->brand_id = $Brand->id;
                $translation->language_id       = $lang;
                $translation->title             = $value['title'];
                $translation->description       = $value['description'];
                $translation->save();
            }
        }

        if ($data->has('forms')) {
            foreach ($data->forms as $form) {
                $print_form = PrintingFormsColor::firstOrNew(['brand_id' => $Brand->id, 'print_form' => $form['form_name']]);
                $print_form->placeholder_text_color = $form['placeholder_text_color'];
                $print_form->primary_background_color = $form['primary_background_color'];
                $print_form->primary_text_color = $form['primary_text_color'];
                $print_form->secondary_background_color = $form['secondary_background_color'];
                $print_form->secondary_text_color = $form['secondary_text_color'];
                $print_form->save();
            }
        }

        return new BrandResource($Brand);
    }

    public function create(Request $data)
    {
        //Step one, validate required data for categories db table
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
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
        $Brand = Brand::firstOrNew(['id' => $data['id']]);

        if ($data->hasFile('icon')) {
            $stored_name          = $data->file('icon')->store('public');
            $Brand->icon = str_replace("public/", "", $stored_name);
        }
        createSlug($data['title'][config('app.locale')], $Brand);
        $Brand->save();

        //Step Three, add translations
        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = BrandProfile::firstOrNew(['brand_id' => $Brand->id, 'language_id' => $lang]);
                $translation->brand_id = $Brand->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->description       = $data['description'][$lang];
                $translation->save();
            }
        }

        if ($data->has('forms')) {
            foreach ($data->forms as $form_name => $form) {
                $print_form = PrintingFormsColor::firstOrNew(['brand_id' => $Brand->id, 'print_form' => $form_name]);
                $print_form->placeholder_text_color = $form['placeholder_text_color'];
                $print_form->primary_background_color = $form['primary_background_color'];
                $print_form->primary_text_color = $form['primary_text_color'];
                $print_form->secondary_background_color = $form['secondary_background_color'];
                $print_form->secondary_text_color = $form['secondary_text_color'];
                $print_form->save();
            }
        }

        return redirect()->back();
    }
    public function delete_icon(Request $data){
        $cat = Brand::find($data['id']);
        $cat->icon = null;
        $cat->save();
    }
}
