<?php

namespace App\Http\Controllers;

use App\DocumentTypeProfile;
use App\Http\Resources\DocumentTypesCollection;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;
use App\DocumentType;
use App\Http\Resources\DocumentTypesResource;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $documentTypes = DocumentType::query();

        if ($term) {
            $documentTypes = $documentTypes->where('id', $mystring)
                ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                    $q->where("title", "like", "%" . $mystring . "%");
                    $q->where('language_id', $lng);
                })
                ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                    $q->where("description", "like", "%" . $mystring . "%");
                    $q->where('language_id', $lng);
                });
        }

        $documentTypes = $documentTypes->paginate(Cookie::get('pages') ?? '5');
        return view('documentTypes.preview', compact(['documentTypes', 'lng']));
    }


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $documentTypes = DocumentType::query()->orderBy('created_at', 'desc');

        if ($term) {
            $documentTypes = $documentTypes->where('id', $mystring)
                ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                    $q->where("title", "like", "%" . $mystring . "%");
                    $q->where('language_id', $lng);
                })
                ->orWhereHas("profiles", function ($q) use ($mystring, $lng) {
                    $q->where("description", "like", "%" . $mystring . "%");
                    $q->where('language_id', $lng);
                });
        }
            return new DocumentTypesCollection($documentTypes->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }


    public function edit(Request $request, $id) {
        $documentTypes = DocumentType::find($id);

        return new DocumentTypesResource($documentTypes);
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('documentTypes.create', [
                'lang'          => Language::all(),
                'documentType'  => DocumentType::find($data['cat_id']),
                'lng'           => $lng,
            ]);
        }
        return view('documentTypes.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function delete_api(Request $data)
    {
            $doc = DocumentType::find($data['id']);// v2 sends one by one
            $doc->delete();
            return new DocumentTypesResource($doc);
    }

    public function delete(Request $data)
    {
        $user = DocumentType::whereIn('id', $data['ids'])->delete();
        return "ok";
    }



    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:document_types,id,' . $data['id'],
            'profiles.el.title' => 'required',
            'profiles.en.description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
         $documentType = DocumentType::firstOrNew(['id' => $data['id']]);
        $documentType->save();
        foreach ($data['profiles'] as $lang => $value) {
            if ($value != NULL) {
                $translation              = DocumentTypeProfile::firstOrNew(['type_id' => $documentType->id, 'language_id' => $lang]);
                $translation->type_id = $documentType->id;
                $translation->language_id = $lang;
                $translation->title       = $value['title'];
                $translation->description = $value['description'];
                $translation->save();
            }
        }

         return new DocumentTypesResource($documentType);
    }


    public function create(Request $data)
    {

        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:document_types,id,' . $data['id'],
            'title' => 'required',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        //Step two, create new row for categories db table
        $documentType = DocumentType::firstOrNew(['id' => $data['id']]);
        $documentType->save();
        foreach ($data['title'] as $lang => $value) {
            if ($value != NULL) {
                $translation              = DocumentTypeProfile::firstOrNew(['type_id' => $documentType->id, 'language_id' => $lang]);
                $translation->type_id = $documentType->id;
                $translation->language_id = $lang;
                $translation->title       = $data['title'][$lang];
                $translation->description = $data['description'][$lang];
                $translation->save();
            }
        }
        return redirect()->back();
    }

}
