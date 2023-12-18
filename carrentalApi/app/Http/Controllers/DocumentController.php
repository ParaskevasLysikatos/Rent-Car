<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;
use App\Models\Document;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Models\ImageLink;
use Auth;
use File;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $documents = Document::query();

        if ($term) {
            $documents = $documents->where('id', $term)
                ->orWhere('path', 'like', '%' . $term . '%')
                ->orWhere('mime_type', 'like', '%' . $term . '%');
        }

        $documents = $documents->paginate(Cookie::get('pages') ?? '5');
        return view('documents.preview', compact(['documents', 'lng']));
    }

    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $documents = Document::query()->orderBy('created_at', 'desc');

        if ($term) {
            $documents = $documents->where('id', $term)
                ->orWhere('path', 'like', '%'.$term.'%')
                ->orWhere('mime_type', 'like', '%'.$term.'%');
        }
     return new DocumentCollection($documents->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }

    public function create_view(Request $data, $lng)
    {
        $types = DocumentType::all();
        if (isset($data['cat_id'])) {
            return view('documents.create', [
                'lang'            => Language::all(),
                'document'        => Document::find($data['cat_id']),
                'lng'             => $lng,
                'types'           => $types
            ]);
        }
        return view('documents.create', ['lang' => Language::all(), 'lng'=>$lng, 'types' => $types]);
    }


    public function edit(Request $request, $id) {
        $document = Document::find($id);

        return new DocumentResource($document);
    }

    public function delete(Request $data)
    {
        $document = Document::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function delete_api(Request $data)
    {
            $doc = Document::find($data['id']); // v2 sends one by one
            $doc->delete();
            return new DocumentResource($doc);
    }


    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
           // 'id'            => 'nullable|numeric',
           // 'type_id'          => 'required',
            'file'          => 'required_without:id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $document                = Document::firstOrNew(['id' => $data['id']]);
        $document->type_id       = $data['type_id'];
        $document->user_id       = Auth::id();

        if ($data->hasFile('file')) {
            if($document->path != null){
                File::delete(storage_path('app/public/'.$document->path));
                $document->path = null;
                $document->mime_type = null;
            }
            $document->name = $data->file('file')->getClientOriginalName();
            $stored_path = $data->file('file')->store('public/documents');
            $document->path = str_replace("public/", "", $stored_path);
            $document->mime_type = $data->file('file')->getMimeType();
        }

        $document->comments      = $data['comments'];
        $document->save();

         return new DocumentResource($document);
    }

    public function create(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'            => 'nullable|numeric',
            'type'          => 'required',
            'file'          => 'required_without:id'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }


        $document                = Document::firstOrNew(['id' => $data['id']]);
        $document->type_id       = $data['type'];
        $document->user_id       = Auth::id();

        if ($data->hasFile('file')) {
            if($document->path != null){
                File::delete(storage_path('app/public/'.$document->path));
                $document->path = null;
                $document->mime_type = null;
            }
            $stored_path = $data->file('file')->store('public/documents');
            $document->path = str_replace("public/", "", $stored_path);
            $document->mime_type = $data->file('file')->getMimeType();
        }

        $document->comments      = $data['comments'];
        $document->save();

//        return "<pre>".print_r($document, 1)."</pre>";

        return redirect()->back();
    }

    public function removeDocumentLink(Request $data) {
        $validator = Validator::make($data->all(), [
            'document_id' => 'required|exists:images,id',
            'document_link_id' => 'required',
            'document_link_type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ($validator->errors()->first());
        }

        ImageLink::where(['document_id' => $data['document_id'], 'document_link_id' => $data['document_link_id'], 'document_link_type' => $data['document_link_type']])->delete();
        return response()->json(['message' => 'Successful removal of document']);
    }
}
