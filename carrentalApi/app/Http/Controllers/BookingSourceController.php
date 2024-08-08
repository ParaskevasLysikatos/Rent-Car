<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceCollection;
use App\Http\Resources\SourceResource;
use App\Models\BookingSource;
use App\Models\BookingSourceProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BookingSourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];

        $booking_sources = BookingSource::query();
        if ($term) {
            $booking_sources = $booking_sources->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }

        $booking_sources = $booking_sources->paginate(Cookie::get('pages') ?? '5');
        return view('booking_source.preview', compact(['booking_sources', 'lng']));
    }

    public function preview_api(Request $request, $lng=null)
    {
        $term = $request['search'];

        $booking_sources = BookingSource::query()->orderBy('created_at', 'desc');
        if ($term) {
            $booking_sources = $booking_sources->where('slug', 'like', "%" . $term . "%")
                ->orWhereHas("profiles", function ($q) use ($term, $lng) {
                    $q->where("title", "like", "%" . $term . "%");
                    $q->where('language_id', $lng);
                });
        }
         return new SourceCollection($booking_sources->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function edit(Request $request, $id) {
        $booking_source = BookingSource::find($id);
        return new SourceResource($booking_source);
    }

    public function create_view(Request $data, $lng)
    {

        if (isset($data['cat_id'])) {
            return view('booking_source.create', [
                'booking_source'   => BookingSource::find($data['cat_id']),
                'lng'             => $lng
            ]);
        }
        return view('booking_source.create', ['lng'=>$lng]);
    }

    public function delete(Request $data)
    {
        BookingSource::whereIn('id', $data['ids'])->delete();
        return "ok";
    }


    public function delete_api(Request $data,$id)
    {
            $booking_source = BookingSource::find($id);//v2 sends one by on del requests
            $booking_source->delete();
            return new SourceResource($booking_source);
    }

    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:booking_sources,id,' . $data['id'],
           // 'title'   => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $booking_source                 = BookingSource::firstOrNew(['id' => $data['id']]);
        $booking_source->agent_id       = $data['agent_id'];
        $booking_source->program_id     = $data['program_id'];

        if (is_array($data['brand_id'])) {
            $booking_source->brand_id      = (int)$data['brand_id.id'];
        } else {
            $booking_source->brand_id      = $data['brand_id'];
        }

        createSlug($data['profiles']['el']['title'], $booking_source);
        $booking_source->save();

        foreach ($data['profiles'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = BookingSourceProfile::firstOrNew(['booking_source_id' => $booking_source->id, 'language_id' => $lang]);
                $translation->booking_source_id = $booking_source->id;
                $translation->language_id       = $lang;
                $translation->title             = $value['title'];
                $translation->description       = $value['description'];
                $translation->save();
            }
        }
        return  new SourceResource($booking_source);
    }


    public function create(Request $data)
    {

        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:booking_sources,id,' . $data['id'],
            'title'   => 'required'
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $booking_source                 = BookingSource::firstOrNew(['id' => $data['id']]);
        $booking_source->agent_id       = $data['agent_id'];
        $booking_source->program_id     = $data['program_id'];
        $booking_source->brand_id       = $data['brand_id'];
        createSlug($data['title'][config('app.locale')], $booking_source);
        $booking_source->save();

        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation                    = BookingSourceProfile::firstOrNew(['booking_source_id' => $booking_source->id, 'language_id' => $lang]);
                $translation->booking_source_id = $booking_source->id;
                $translation->language_id       = $lang;
                $translation->title             = $data['title'][$lang];
                $translation->description       = $data['description'][$lang];
                $translation->save();
            }
        }

        if ($data->ajax()) {
            return response()->json($booking_source->append('profile_title'));
        }

        return redirect()->back();
    }

    public function search_ajax(Request $data, $lng)
    {
        $mystring        = $data['search'];
        $booking_sources = BookingSource::where('id', $mystring)->orWhere('slug', 'like', "%" . $mystring . "%")
                                         ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                                             $q->where("title", "like", "%" . $mystring . "%");
                                             $q->where('language_id', $lng);
                                         })->take(Cookie::get('pages') ?? '5')->get();
        $booking_sources->each->append('profile_title');
        return response()->json($booking_sources);
    }
}
