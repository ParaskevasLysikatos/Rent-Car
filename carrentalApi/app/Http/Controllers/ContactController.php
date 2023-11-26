<?php

namespace App\Http\Controllers;

use DB;
use Cookie;
use Session;
use App\Agent;
use Validator;
use App\Contact;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function preview(Request $data)
    {
        $contacts = $this->searchModel($data);

        $contacts = $contacts->paginate(Cookie::get('pages') ?? '5');
        return view('contacts.preview')->with(['contacts' => $contacts]);
    }

    public function preview_api(Request $request) {
        $contacts = $this->searchModel($request);
         $contacts = $contacts->filter($request);
        return new ContactCollection($contacts->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function edit(Request $request, $id) {
        $contact = Contact::find($id);

        return new ContactResource($contact);
    }

    public function create_view(Request $data)
    {
        if (isset($data['cat_id'])) {
            return view('contacts.create', [
                'contact' => Contact::find($data['cat_id'])
            ]);
        }
        return view('contacts.create');
    }

    public function searchModel(Request $data) {
        $mystring = $data['search'];
        $agent = $data['agent'];
        $sub_agent = $data['sub_agent'];

        $contacts  = Contact::orderBy('created_at', 'desc')->where(function ($q) use ($mystring) {
            $q->where(DB::raw('CONCAT(COALESCE(firstname,""), " ", COALESCE(lastname,""))'), 'like', "%" . $mystring . "%")
            ->orWhere('email', 'like', "%" . $mystring . "%")
            ->orwhere('phone', 'like', "%" . $mystring . "%");
        });

        if ($sub_agent) {
            $contacts = $contacts->whereHas('agent', function($q) use ($sub_agent) {
                $q->where('agents.id', $sub_agent);
            });
        } else if ($agent) {
            $contacts = $contacts->whereHas('agent', function($q) use ($agent) {
                $q->where('agents.id', $agent);
            });
        }
        if (isset($data['driver'])) {
            $driver = $data['driver'];

            if ($driver) {
                $contacts = $contacts->whereHas('driver', function ($q) use ($driver) {
                    $q->where('drivers.id', $driver);
                });
            } else {
                $contacts = $contacts->whereDoesntHave('driver');
            }
        }

        return $contacts;
    }

    public function search_ajax(Request $data){
        $contacts = $this->searchModel($data)->take(Cookie::get('pages') ?? 10)->get();
        $contacts->each->append('customer_id');
        return response()->json($contacts);
    }

    public function update_store_api(Request $data){
        $validator = Validator::make($data->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'phone'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $contact = Contact::firstOrNew(['id' => $data['id']]);
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
        $contact->afm                       = $data['afm'];
        $contact->identification_number     = $data['identification_number'];
        $contact->identification_country    = $data['identification_country'];
        $contact->identification_created    = $data['identification_created'];
        $contact->identification_expire     = $data['identification_expire'];
        $contact->save();

        if (isset($data['agent_id']) && $data['agent_id']) {
            $agent = Agent::find($data['agent_id']);
            $agent->sub_contacts()->attach($contact->id);
        }
        return new ContactResource($contact);
    }


    public function create(Request $data) {
        if (!$data->ajax()) {
            if (isset($data['id']) && $data['id'] != '')
                Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
            else
                Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
            Session::flash('alert-class', 'alert-success');
        }

        $validator = Validator::make($data->all(), [
            'first_name' => 'required',
            'phone'     => 'required',
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $contact = Contact::firstOrNew(['id' => $data['id']]);
        $contact->firstname                 = $data['first_name'];
        $contact->lastname                  = $data['last_name'];
        $contact->email                     = $data['email'];
        $contact->phone                     = $data['phone'];
        $contact->mobile                    = $data['mobile'];
        $contact->address                   = $data['address'];
        $contact->zip                       = $data['zip'];
        $contact->city                      = $data['city'];
        $contact->country                   = $data['country'];
        $contact->birthday                  = $data['birthday'];
        $contact->afm                       = $data['afm'];
        $contact->identification_number     = $data['identification_number'];
        $contact->identification_country    = $data['identification_country'];
        $contact->identification_created    = $data['identification_created'];
        $contact->identification_expire     = $data['identification_expire'];
        $contact->save();

        if (isset($data['agent_id']) && $data['agent_id']) {
            $agent = Agent::find($data['agent_id']);
            $agent->sub_contacts()->attach($contact->id);
        }

        if ($data->ajax()) {
            $contact->model = Contact::class;
            $contact->account_id = $contact->id;
            $contact->name = $contact->full_name;
            return response()->json($contact->append('customer_id'));
        }

        return redirect()->back();
    }

    public function delete(Request $data)
    {
        $contact = Contact::whereIn('id', $data['ids'])->delete();
        return "contact deleted";
    }

    public function delete_api(Request $data)
    {
            $con = Contact::find($data['id']); // v2 sends one by one
            $con->delete();
            return new ContactResource($con);
    }
}