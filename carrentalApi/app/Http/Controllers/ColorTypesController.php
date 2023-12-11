<?php

namespace App\Http\Controllers;

use App\ColorTypes;
use App\Http\Resources\ColorTypeCollection;
use App\Http\Resources\ColorTypeResource;
use Cookie;
use Illuminate\Http\Request;

class ColorTypesController extends Controller
{

    public function index(Request $request, $lng)
    {
        $color_types = ColorTypes::paginate(Cookie::get('pages') ?? '5');

        return view('color_types.preview', compact(['color_types', 'lng']));
    }

    public function preview_api(Request $request, $lng=null) {
        $color_types = ColorTypes::query()->orderBy('created_at', 'desc');
       return new ColorTypeCollection($color_types->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function create(Request $request, $lng) {
        $color_type = ColorTypes::find($request['cat_id']);

        return view('color_types.create', compact(['color_type', 'lng']));
    }

    public function store(Request $request, $lng) {
        $color_type = ColorTypes::firstOrNew(['id' => $request->id]);
        $color_type->title = $request->title;
        $color_type->international_title = $request->international_title;
        $color_type->hex_code = $request->hex_code;
        $color_type->save();

        return view('color_types.create', compact(['color_type', 'lng']));
    }

    public function edit(Request $request, $id)
    {
        $color_type = ColorTypes::find($id);
        return new ColorTypeResource($color_type);
    }

    public function update_store_api(Request $request){
        $color_type = ColorTypes::firstOrNew(['id' => $request->id]);
        $color_type->title = $request->title;
        $color_type->international_title = $request->international_title;
        $color_type->hex_code = $request->hex_code;
        $color_type->save();
        return new ColorTypeResource($color_type);
    }

    public function delete(Request $request, $lng)
    {
        foreach ($request->ids as $id) {
            $color_type = ColorTypes::find($id);
            $color_type->delete();
        }
    }

    public function delete_api(Request $request) {
            $color_type = ColorTypes::find($request['id']); // v2 sends one by one requests
            $color_type->delete();
            return new ColorTypeResource($color_type);
    }

    
}