<?php

namespace App\Http\Controllers;

use App\Http\Resources\VehicleStatusCollection;
use App\Http\Resources\VehicleStatusResource;
use App\Language;
use App\Status;
use App\StatusProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cookie;
use Session;
class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $statuses = Status::query();

        if ($term) {
            $statuses = $statuses->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $statuses = $statuses->paginate(Cookie::get('pages') ?? '5');
        return view('status.preview', compact(['statuses', 'lng']));
    }


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $status = Status::query()->orderBy('created_at', 'desc');

        if ($term) {
            $status = $status->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

    return new VehicleStatusCollection($status->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));

    }

    public function create_view(Request $data, $lng)
    {

        if (isset($data['cat_id'])) {
            return view('status.create', [
                'status'         => Status::find($data['cat_id']),
                'lang'   => Language::all(),
                'lng'             => $lng
            ]);
        }
        return view('status.create', ['lang' => Language::all(), 'lng'=>$lng]);
    }

    public function delete_api(Request $data)
    {
            $status = Status::find($data['id']);// v2 sends one by one
            $status->delete();
            return new VehicleStatusResource($status);
    }


     public function edit(Request $request, $id) {
        $status = Status::find($id);

        return new VehicleStatusResource($status);
    }

public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:vehicle_status,id,' . $data['id'],
            'profiles.el.title'   => 'required',
            'profiles.el.description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
    $status                 = Status::firstOrNew(['id' => $data['id']]);
        createSlug($data['profiles']['el']['title'], $status);
        $status->save();
        foreach ($data['profiles'] as $lang => $value) {
            if ($value != NULL) {
                $translation                        = StatusProfile::firstOrNew(['vehicle_status_id' => $status->id, 'language_id' => $lang]);
                $translation->vehicle_status_id     = $status->id;
                $translation->language_id           = $lang;
                $translation->title                 = $value['title'];
                $translation->description           = $value['description'];
                $translation->save();
            }
        }
         return new VehicleStatusResource($status);
}


    public function create(Request $data, $lng)
    {

        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:vehicle_status,id,' . $data['id'],
            'title'   => 'required',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $status                 = Status::firstOrNew(['id' => $data['id']]);
        createSlug($data['title'][config('app.locale')], $status);
        $status->save();


        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                        = StatusProfile::firstOrNew(['vehicle_status_id' => $status->id, 'language_id' => $lang]);
                $translation->vehicle_status_id     = $status->id;
                $translation->language_id           = $lang;
                $translation->title                 = $data['title'][$lang];
                $translation->description           = $data['description'][$lang];
                $translation->save();
            }
        }

        return redirect()->route('edit_status_view', ['locale'=>$lng, 'cat_id'=>$status->id]);
    }

    public function search_ajax(Request $data){
        return 'To do';
    }

}
