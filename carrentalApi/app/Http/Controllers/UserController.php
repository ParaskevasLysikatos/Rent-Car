<?php

namespace App\Http\Controllers;

use App\Language;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Cookie;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview(Request $data, $lng)
    {
        $term = $data['search'];
        $users = User::query();

        if ($term) {
            $users = $users->where('email', 'like', "%" . $term . "%")
                ->orwhere('name', 'like', "%" . $term . "%")
                ->orwhere('phone', 'like', "%" . $term . "%");
        }

        $users = $users->paginate(Cookie::get('pages') ?? '5');
        return view('users.preview', compact(['users', 'lng']));
    }


    public function preview_api(Request $data, $lng=null)
    {
        $term = $data['search'];
        $users = User::query()->orderBy('created_at', 'desc');

        if ($term) {
            $users = $users->where('email', 'like', "%" . $term . "%")
                ->orwhere('name', 'like', "%" . $term . "%")
                ->orwhere('phone', 'like', "%" . $term . "%");
        }

        $users = $users->filter($data);

        return new UserCollection($users->filter($data)->paginate($data->get('per_page') ?? '5'), ['*'], 'page', $data->get('page'));
    }


    public function edit(Request $request, $id) {
        $user = User::find($id);
        return new UserResource($user);
    }

    public function search_ajax(Request $data){
        $mystring = $data['search'];
        $users  = User::where('email', 'like', "%" . $mystring . "%")
            ->orwhere('name', 'like', "%" . $mystring . "%")
            ->orwhere('phone', 'like', "%" . $mystring . "%")
            ->take(Cookie::get('pages') ?? 10)->get();
        return $users;
    }

    public function create_view(Request $data, $lng)
    {
        $rules = UserRole::all();
        if (isset($data['cat_id'])) {
            return view('users.create', [
                'lang'            => Language::all(),
                'user'         => User::find($data['cat_id']),
                'lng'             => $lng,
                'rules' => $rules
            ]);
        }
        return view('users.create', ['lang' => Language::all(), 'lng'=>$lng, 'rules' => $rules]);
    }



    public function delete(Request $data)
    {
        $users = User::whereIn('id', $data['ids'])->get();
        foreach ($users as $user) {
            $user->delete();
        }

        return "ok";
    }


    public function delete_api(Request $data)
    {
            $users = User::find($data['id']);// v2 sends one by one
            $users->delete();
            return new UserResource($users);
    }

    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'id'         => 'nullable|numeric',
            'name'       => 'required',
            'driver_id'  => 'required',
            'phone'      => 'nullable',
            'email'      => 'required|email',
            'username'   => 'required',
            'role_id'       => 'required|exists:user_roles,id',
            'password'   => 'nullable|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
         $user                 = User::firstOrNew(['id' => $data['id']]);
        $user->name           = $data['name'];
        $user->username       = $data['username'];
        $user->email          = $data['email'];
        $user->phone          = $data['phone'];

        if(is_array($data['driver_id'])){
            $user->driver_id      = (int)$data['driver_id.id'];
        }else{
            $user->driver_id      = $data['driver_id'];
        }

        if (is_array($data['station_id'])) {
            $user->station_id      =  (int)$data['station_id.id'];
        } else {
            $user->station_id      = $data['station_id'];
        }

        if (isset($data['id']) && $data['id'] != '' && $data['password']==''){
            // not change password
        }else{
            $user->password       = Hash::make($data['password']);
        }

        $user->role_id        = $data['role_id'];
        $user->save();
        return new UserResource($user);
    }



    public function create(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'         => 'nullable|numeric',
            'name'       => 'required',
            'driver_id'  => 'required',
            'phone'      => 'nullable',
            'email'      => 'required|email',
            'username'   => 'required',
            'role'       => 'required|exists:user_roles,id',
            'password'   => 'nullable|min:6'
        ]);

        if ($validator->fails()) {
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        if (isset($data['id']) && $data['id'] != ''){
            $email_exists = User::where('email', $data['email'])->where('id', '!=', $data['id'])->first();
            if($email_exists){
                Session::flash('message', __('Το email υπάρχει ήδη.') );
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back()->withInput();
            }
        }else{
            if(!isset($data['password']) || ( isset($data['password']) && $data['password']=='' )){
                Session::flash('message', __('Παρακαλώ προσθέστε τον κωδικό.') );
                Session::flash('alert-class', 'alert-danger');
                return redirect()->back()->withInput();
            }
        }

        $user                 = User::firstOrNew(['id' => $data['id']]);
        $user->name           = $data['name'];
        $user->username       = $data['username'];
        $user->email          = $data['email'];
        $user->phone          = $data['phone'];
        $user->driver_id      = $data['driver_id'];
        $user->station_id     = $data['station_id'];

        if (isset($data['id']) && $data['id'] != '' && $data['password']==''){
            //To do
        }else{
            $user->password       = Hash::make($data['password']);
        }

        $user->role_id        = $data['role'];
        $user->save();

        return redirect()->back();
    }

}
