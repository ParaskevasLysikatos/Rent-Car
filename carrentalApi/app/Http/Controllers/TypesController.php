<?php

namespace App\Http\Controllers;

use App\Category;
use App\Characteristic;
use App\Option;
use App\TypeProfile;
use App\Vehicle;
use Illuminate\Http\Request;
use App\Type;
use App\Language;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;
use App\Http\Resources\TypeCollection;
use App\Http\Resources\TypeResource;
use Illuminate\Support\Facades\Storage;

class TypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview(Request $data, $lng)
    {
        $types = $this->searchModel($data['search'], $lng)->paginate(Cookie::get('pages') ?? '5');
        return view('types.preview', compact(['types', 'lng']));
    }


    public function preview_api(Request $data, $lng=null)
    {
        $types = $this->searchModel($data['search'], $lng);
        $types = Type::query()->orderBy('created_at', 'asc');
         $types = $types->filter($data);
        return new TypeCollection($types->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }


    private function searchModel($text, $lng) {
        $type = Type::where('id', $text);
        if (mb_strlen($text) > 1) {
            $type = $type->orWhere('international_code', 'like', '%'.$text.'%')
                ->orWhereHas("profiles", function ($q) use ($text, $lng) {
                    $q->where("title", "like", "%" . $text . "%");
                    $q->where('language_id', $lng);
                });
        }
        return $type->orWhereHas('category', function ($q) use ($text, $lng) {
                    $q->whereHas('profiles', function ($prof_q) use ($text, $lng) {
                        $prof_q->where("title", "like", "%" . $text . "%");
                        $prof_q->where('language_id', $lng);
                    });
                });
    }

    public function search_ajax(Request $data, $lng){
        $mystring = $data['search'];
        $types    = $this->searchModel($mystring, $lng)->with('category')->take(Cookie::get('pages') ?? '5')->get();
        return response()->json($types);
    }

    public function create_view(Request $data, $lng)
    {
        $options         = Option::all();
        $characteristics = Characteristic::all();
        $categories      = Category::all();
        if (isset($data['cat_id'])) {
            return view('types.create', [
                'lang'            => Language::all(),
                'type'            => Type::find($data['cat_id']),
                'options'         => $options,
                'characteristics' => $characteristics,
                'categories'      => $categories,
                'lng'            => $lng,
            ]);
        }
        return view('types.create', [
            'lang'            => Language::all(),
            'options'         => $options,
            'characteristics' => $characteristics,
            'categories'      => $categories,
            'lng'            => $lng,
        ]);
    }

    public function storeFromApi(Request $request,Type $type){
        $validator = Validator::make($request->all(), [
            'id'             => 'nullable|numeric',
           // 'icon'           => 'nullable|mimes:jpeg,png,jpg',
            'category_id'       => 'required|exists:categories,id',
            'options'         => 'nullable|exists:options,id',
            'characteristics' => 'nullable|exists:characteristics,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $type->category_id          = $request['category_id'];
        $type->min_category         = $request['min_category'];
        $type->max_category         = $request['max_category'];
        $type->excess               = $request['excess'];
        $type->international_code   = $request['international_code'];

        if ($request->hasFile('icon')) {
            $stored_name = $request->file('icon')->store('public');
            $type->icon  = str_replace("public/", "", $stored_name);
        }
        createSlug($request['profiles']['el']['title'], $type);
        $type->save();
        $type->images()->sync($request['images']);

        //Step Three, add translations
        foreach ($request['profiles'] as $lang => $profile) {
            if ($profile != NULL) {
                $translation              = TypeProfile::firstOrNew(['type_id' => $type->id, 'language_id' => $lang]);
                $translation->type_id     = $type->id;
                $translation->language_id = $lang;
                $translation->title       = $profile['title'];
                $translation->description = $profile['description'];
                $translation->save();
            }
        }

        $type->options()->sync($request['options']);
        $type->characteristics()->sync($request['characteristics']);
        return $type;
    }

    public function delete(Request $data)
    {
        $types = Type::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function delete_api(Request $data)
    {
        $types = Type::find($data['id']);
        $types->delete();
        return new TypeResource($types);
    }

    public function edit(Request $request, $id) {
        $type = Type::find($id);
        return new TypeResource($type);
    }

    public function upload(Request $data) {
        $validator = Validator::make($data->all(), [
            'file'           => 'nullable|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $type = Type::find($data['id']);
        if ($data->hasFile('file')) {
            $stored_name = $data->file('file')->store('public');
            $type->icon  = str_replace("public/", "", $stored_name);
            $type->save();
        }

        return new TypeResource($type);
    }

    public function uploadRemove(Request $data,$id)
    {
        $type = Type::find($id);
            Storage::delete('public/' .$type->icon);
            $type->icon=null;
            $type->save();
        return new TypeResource($type);
    }




    public function update(Request $request, $id)
    {
        $type = Type::findOrFail($id);
        $type = $this->storeFromApi($request, $type);

        return new TypeResource($type);
    }


    public function createApi(Request $request) {
        $type = new Type();
        $type =$this->storeFromApi($request, $type);
        return new TypeResource($type);
    }

    public function delete_type_image(Request $data)
    {
        // TypeImage::where('image_id', $data['id'])->delete();
        return "ok";
    }

    public function create(Request $data)
    {
        //Step one, validate required data for categories db table
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ο τύπος ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Ο τύπος δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'             => 'nullable|numeric',
            'icon'           => 'nullable|mimes:jpeg,png,jpg',
            'category'       => 'required|exists:categories,id',
            'option'         => 'nullable|exists:options,id',
            'characteristic' => 'nullable|exists:characteristics,id',
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        //Step two, create new row for categories db table
        $type = Type::firstOrNew(['id' => $data['id']]);

        if (isset($data['id'])) {
            $type->deleteCharacteristics();
        }

        $type->category_id          = $data['category'];
        $type->min_category         = $data['min_category'];
        $type->max_category         = $data['max_category'];
        $type->excess               = $data['excess'];
        $type->international_code   = $data['international_code'];

        if ($data->hasFile('icon')) {
            $stored_name = $data->file('icon')->store('public');
            $type->icon  = str_replace("public/", "", $stored_name);
        }
        createSlug($data['title'][config('app.locale')], $type);
        $type->save();
        $type->addOptionsFromRequest();
        $type->addImages();

        if ($data['characteristic'])
            foreach (Characteristic::whereIn('id', $data['characteristic'])->get() as $characteristic) {
                $type->addCharacteristic($characteristic);
            }

        //Step Three, add translations
        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation              = TypeProfile::firstOrNew(['type_id' => $type->id, 'language_id' => $lang]);
                $translation->type_id     = $type->id;
                $translation->language_id = $lang;
                $translation->title       = $data['title'][$lang];
                $translation->description = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->back();
    }
}