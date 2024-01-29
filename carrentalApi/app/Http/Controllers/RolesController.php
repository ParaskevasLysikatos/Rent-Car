<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolesCollection;
use App\Http\Resources\RolesResource;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $roles = UserRole::query();

        if ($term) {
            $roles = $roles->where('title', 'like', "%" . $term . "%")
                ->orwhere('description', 'like', "%" . $term . "%");
        }

        $roles = $roles->paginate(Cookie::get('pages') ?? '5');
        return view('roles.preview', compact(['roles', 'lng']));
    }


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $roles = UserRole::query()->orderBy('created_at', 'desc');

        if ($term) {
            $roles = $roles->where('title', 'like', "%" . $term . "%")
                ->orwhere('description', 'like', "%" . $term . "%");
        }
         return new RolesCollection($roles->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }

    public function create_view(Request $data, $lng)
    {

        if (isset($data['cat_id'])) {
            return view('roles.create', [
                'role'         => UserRole::find($data['cat_id']),
                'lng'             => $lng
            ]);
        }
        return view('roles.create', ['lng'=>$lng]);
    }

    public function delete(Request $data)
    {
        $user = UserRole::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function delete_api(Request $data)
    {
            $role = UserRole::find($data['id']);// v2 sends one by one
            $role->delete();
            return new RolesResource($role);
    }


       public function edit(Request $request, $id) {
        $roles = UserRole::find($id);

        return new RolesResource($roles);
    }


       public function update_store_api(Request $data){
        //Step two, create new row for categories db table
        $validator = Validator::make($data->all(), [
            'id'   => 'required|unique:user_roles,id,' . $data['id'],
            'title' => 'required',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $roles = UserRole::firstOrNew(['id' => $data['id']]);
        if (isset($data['id']) && $data['id'] != ''){}
        else
            $roles->id = $data['id'];
        $roles->title = $data['title'];
        $roles->description = $data['description'];


        $roles->save();


        return new RolesResource($roles);
    }


    public function create(Request $data)
    {

        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Το χαρακτηριστικό ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Το χαρακτηριστικό δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:user_roles,id,' . $data['id'],
            'id_name'   => 'required',
            'title' => 'required',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        //Step two, create new row for categories db table
        $roles = UserRole::firstOrNew(['id' => $data['id']]);
        if (isset($data['id']) && $data['id'] != ''){}
        else
            $roles->id = $data['id_name'];
        $roles->title = $data['title'];
        $roles->description = $data['description'];


        $roles->save();


        return redirect()->back();
    }

}
