<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverEmpCollection;
use App\Http\Resources\DriverResource;
use App\Http\Resources\DriverEmpResource;
use App\Models\Contact;
use App\Models\Driver;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DriversController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview(Request $data, $lng)
    {
        $drivers = $this->searchModel($data)->paginate(Cookie::get('pages') ?? '5');
        return view('drivers.preview', compact(['drivers', 'lng']));
    }


    public function preview_api(Request $request)
    {
        $drivers = $this->searchModel($request)->filter($request);
        return new DriverCollection($drivers->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }



    public function previewEmp(Request $request)
    {
        $drivers = $this->searchModel($request)->filter($request);

        if ($request->wantsJson()) {
            return new DriverEmpCollection($drivers->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
        }
    }

    public function editEmp(Request $request, $id)
    {
        $driver = Driver::find($id);

        return new DriverEmpResource($driver);
    }


    public function edit(Request $request, $id) {
        $driver = Driver::find($id);

        return new DriverResource($driver);
    }

    /**
     * Return the model after setting up the query
     *
     * @param Request $data
     * @return App\Driver search model
     */
    public function searchModel(Request $data) {
        $mystring = $data['search'];
        $rental = $data['rentals'];

        $drivers = Driver::query();

        if ($mystring) {
            $drivers  = $drivers->where('id', $mystring)->orWhereHas('contact', function ($q) use ($mystring) {
                $q->where(DB::raw('CONCAT(lastname, " ", firstname)'), 'like',  $mystring . "%")
                ->orwhere('licence_number', 'like', '%'. $mystring. '%')
                ->orwhere('email', 'like', '%'. $mystring .'%')
                ->orwhere('phone', 'like', "%" . $mystring . "%");
            });
        }
        if ($data->has('role')) {
            $drivers = $drivers->where('role', $data['role']);
        }
        if ($rental) {
            $drivers = $drivers->whereHas('rentals', function($q) use ($rental) {
                $q->where('rentals.id', $rental);
            });
        }

        return $drivers;
    }

    public function search_ajax(Request $data){
        $drivers = $this->searchModel($data)->take(Cookie::get('pages') ?? 10)->get();
        $drivers->each->append(['phone']);
        return response()->json($drivers);
    }

    public function search_with_contacts_ajax(Request $data) {
        $drivers = $this->searchModel($data)->take(Cookie::get('pages') ?? 5)->get();
        $data->merge([
            'driver' => null
        ]);
        $contacts = (new ContactController())->searchModel($data)->take(Cookie::get('pages') ?? 5)->get();
        $drivers->each->append(['customer_id', 'phone']);
        $contacts->each->append(['customer_id']);

        $res = $drivers->merge($contacts);
        return response()->json($res);
    }

    public function searchData(Request $data, $lng){
        $mystring        = $data['search'];
        $exist           = Driver::whereHas('companies', function ($q) use ($data) {
            $q->where('id', $data['company']);
        })->pluck('id');
        $drivers = Driver::whereHas('contact', function($q) use($mystring) {
                        $q->where('firstname', 'like', "%" . $mystring . "%");
                    })
                    ->whereNotIn('id', $exist)
                    ->orWhere('drivers.lastname', 'like', "%" . $mystring . "%")
                    ->whereNotIn('id', $exist)
                    ->take(5)
                    ->get();
        return $drivers;
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('drivers.create', [
                'lang'           => Language::all(),
                'driver' => Driver::find($data['cat_id']),
                'lng'            => $lng
            ]);
        }
        return view('drivers.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function delete(Request $data)
    {
        $users = Driver::whereIn('id', $data['ids'])->delete();
        return "driver deleted";
    }

    public function delete_api(Request $data)
    {
            $driver = Driver::find($data['id']);
            $driver->delete();
            return new DriverResource($driver);
    }


    public function update_store_api(Request $data){
        $idCheckContact=$data['contact_id'];
        $idDriver=$data['id'];

        $validator = Validator::make($data->all(), [
            'id'                    => 'nullable|numeric|exists:drivers',
            'firstname'            => 'required',
            'lastname'             => 'required',
            'email'                 => "nullable|unique:contacts,email,$idCheckContact",
            'phone'                 => "nullable|unique:contacts,phone,$idCheckContact",
            'address'               => 'nullable',
            'zip'                   => 'nullable',
            'city'                  => 'nullable',
            'country'               => 'nullable',
            'birthday'              => 'nullable',
            'fly_number'            => 'nullable',
            'notes'                 => 'nullable',
            'licence_number'        => "required|unique:drivers,licence_number,$idDriver",
            'licence_country'       => 'nullable',
            'licence_created'       => 'nullable',
            'licence_expire'        => 'nullable',
            'identification_number' => "required|unique:contacts,identification_number,$idCheckContact",
            'role'                  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        if (isset($data['id']) && $data['id'] != '') {
            $driver = Driver::find($data['id']);

            $contact = Contact::find($driver->contact_id);
            $contact->firstname                 = $data['firstname'];
            $contact->lastname                  = $data['lastname'];
            $contact->email                     = $data['email'];
            $contact->phone                     = $data['phone'];
            $contact->mobile                    = $data['mobile'];
            $contact->address                   = $data['address'];
            $contact->zip                       = $data['zip'];
            $contact->city                      = $data['city'];
            $contact->country                   = $data['country'];
            $contact->birthday                  = $data['birthday'];
            $contact->birth_place               = $data['birth_place'];
            $contact->afm                       = $data['afm'];
            $contact->identification_number     = $data['identification_number'];
            $contact->identification_country    = $data['identification_country'];
            $contact->identification_created    = $data['identification_created'];
            $contact->identification_expire     = $data['identification_expire'];
            $contact->save();

            $update_driver = Driver::updateOrCreate(['id' => $data['id']],
                [
                    'contact_id'            => $contact->id,
                    'notes'                 => $data['notes'],
                    'licence_number'        => $data['licence_number'],
                    'licence_country'       => $data['licence_country'],
                    'licence_created'       => $data['licence_created'],
                    'licence_expire'        => $data['licence_expire'],
                    'role'                  => $data['role']
                ]);
            if ($data->has('companies')) {
                $companies = $data['companies'];
                $update_driver->handleCompanies($companies);
            }

            $update_driver->documents()->sync($data['documents']);

            return new DriverResource($driver);
        }

        $new_contact = new Contact();
        $new_contact->firstname                 = $data['firstname'];
        $new_contact->lastname                  = $data['lastname'];
        $new_contact->email                     = $data['email'];
        $new_contact->phone                     = $data['phone'];
        $new_contact->mobile                    = $data['mobile'];
        $new_contact->address                   = $data['address'];
        $new_contact->zip                       = $data['zip'];
        $new_contact->city                      = $data['city'];
        $new_contact->country                   = $data['country'];
        $new_contact->birthday                  = $data['birthday'];
        $new_contact->birth_place               = $data['birth_place'];
        $new_contact->afm                       = $data['afm'];
        $new_contact->identification_number     = $data['identification_number'];
        $new_contact->identification_country    = $data['identification_country'];
        $new_contact->identification_created    = $data['identification_created'];
        $new_contact->identification_expire     = $data['identification_expire'];
        $new_contact->save();

        $new_driver = Driver::create(
            [
                'contact_id'            => $new_contact->id,
                'notes'                 => $data['notes'],
                'licence_number'        => $data['licence_number'],
                'licence_country'       => $data['licence_country'],
                'licence_created'       => $data['licence_created'],
                'licence_expire'        => $data['licence_expire'],
                'role'                  => $data['role']
            ]
        );

        if ($data->has('companies')) {
            $companies = $data['companies'];
            $new_driver->handleCompanies($companies);
        }

        $new_driver->documents()->sync($data['documents']);

        return new DriverResource($new_driver);
    }

    public function create(Request $data, $lng)
    {
        if (!$data->ajax()) {
            if (isset($data['id']) && $data['id'] != '')
                Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
            else
                Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
            Session::flash('alert-class', 'alert-success');
        }

        $validator = Validator::make($data->all(), [
            'id'                    => 'nullable|numeric|exists:drivers',
            'firstname'            => 'required',
            'lastname'             => 'required',
            'email'                 => 'nullable',
            'phone'                 => 'nullable',
            'address'               => 'nullable',
            'zip'                   => 'nullable',
            'city'                  => 'nullable',
            'country'               => 'nullable',
            'birthday'              => 'nullable',
            'fly_number'            => 'nullable',
            'notes'                 => 'nullable',
            'licence_number'        => 'required',
            'licence_country'       => 'nullable',
            'licence_created'       => 'nullable',
            'licence_expire'        => 'nullable',
            'identification_number' => 'required',
            'role'                  => 'required'
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        if (isset($data['id']) && $data['id'] != '') {
            $driver = Driver::find($data['id']);

            $contact = Contact::find($driver->contact_id);
            $contact->firstname                 = $data['firstname'];
            $contact->lastname                  = $data['lastname'];
            $contact->email                     = $data['email'];
            $contact->phone                     = $data['phone'];
            $contact->mobile                    = $data['mobile'];
            $contact->address                   = $data['address'];
            $contact->zip                       = $data['zip'];
            $contact->city                      = $data['city'];
            $contact->country                   = $data['country'];
            $contact->birthday                  = $data['birthday'];
            $contact->birth_place               = $data['birth_place'];
            $contact->afm                       = $data['afm'];
            $contact->identification_number     = $data['identification_number'];
            $contact->identification_country    = $data['identification_country'];
            $contact->identification_created    = $data['identification_created'];
            $contact->identification_expire     = $data['identification_expire'];
            $contact->save();

            $update_driver = Driver::updateOrCreate(['id' => $data['id']],
                [
                    'contact_id'            => $contact->id,
                    'notes'                 => $data['notes'],
                    'licence_number'        => $data['licence_number'],
                    'licence_country'       => $data['licence_country'],
                    'licence_created'       => $data['licence_created'],
                    'licence_expire'        => $data['licence_expire'],
                    'role'                  => $data['role']
                ]);
            if ($data->has('companies')) {
                $companies = $data['companies'];
                $update_driver->handleCompanies($companies);
            }
            $update_driver->addDocuments();//needed for merge for v1

            if ($data->ajax()) {
                return response()->json($update_driver);
            }

            return redirect()->back();
        }

        $validator = Validator::make($data->all(), [
            'email'                 => 'required|unique:contacts',
            'licence_number'        => 'nullable|unique:drivers',
            'identification_number' => 'nullable|unique:contacts'
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $new_contact = new Contact();
        $new_contact->firstname                 = $data['firstname'];
        $new_contact->lastname                  = $data['lastname'];
        $new_contact->email                     = $data['email'];
        $new_contact->phone                     = $data['phone'];
        $new_contact->mobile                    = $data['mobile'];
        $new_contact->address                   = $data['address'];
        $new_contact->zip                       = $data['zip'];
        $new_contact->city                      = $data['city'];
        $new_contact->country                   = $data['country'];
        $new_contact->birthday                  = $data['birthday'];
        $new_contact->birth_place               = $data['birth_place'];
        $new_contact->afm                       = $data['afm'];
        $new_contact->identification_number     = $data['identification_number'];
        $new_contact->identification_country    = $data['identification_country'];
        $new_contact->identification_created    = $data['identification_created'];
        $new_contact->identification_expire     = $data['identification_expire'];
        $new_contact->save();

        $new_driver = Driver::create(
            [
                'contact_id'            => $new_contact->id,
                'notes'                 => $data['notes'],
                'licence_number'        => $data['licence_number'],
                'licence_country'       => $data['licence_country'],
                'licence_created'       => $data['licence_created'],
                'licence_expire'        => $data['licence_expire'],
                'role'                  => $data['role']
            ]
        );

        if ($data->has('companies')) {
            $companies = $data['companies'];
            $new_driver->handleCompanies($companies);
        }

        $new_driver->addDocuments();//needed for merge for v1

        if ($data->ajax()) {
            return response()->json($new_driver->append('customer_id'));
        }

        return redirect()->route('edit_driver_view', ['locale'=>$lng, 'cat_id'=>$new_driver->id]);
    }



}
