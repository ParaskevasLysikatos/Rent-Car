<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Agent;
use App\Http\Resources\AgentCollection;
use App\Http\Resources\AgentResource;
use App\Http\Resources\ProgramCollection;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\SubAccount;
use App\Http\Resources\SubAccountResource;
use App\Program;
use App\ProgramProfile;
use Cookie;
use Session;

class AgentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function preview(Request $data, $lng)
    {
        $agents = Agent::query();

        $term = $data['search'];
        if ($term) {
            $agents = $agents->where('name', 'like', "%" . $term . "%")
                ->orwhere('commission', 'like', "%" . $term . "%");
        }

        $agents = $agents->paginate(Cookie::get('pages') ?? '5');
        return view('agents.preview', compact(['agents', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $agents = Agent::query()->orderBy('created_at', 'desc');

        $term = $request['search'];
        if ($term) {
            $agents = $agents->where('name', 'like', "%" . $term . "%")
                ->orwhere('commission', 'like', "%" . $term . "%");
        }

        $agents = $agents->filter($request);
        // return response()->json($agents->get());
     return new AgentCollection($agents->filter($request)->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }



    public function edit(Request $request, $id)
    {
        $agent = Agent::find($id);
        return new AgentResource($agent);
    }


    public function program(Request $request)
    {
        $program = ProgramProfile::all()->where('language_id', 'el');
        return  $program;
    }

    public function create_view(Request $data, $lng)
    {

        if (isset($data['cat_id'])) {
            return view('agents.create', [
                'agent'         => Agent::find($data['cat_id']),
                'lng'             => $lng
            ]);
        }
        return view('agents.create', ['lng' => $lng]);
    }

    public function delete_api(Request $data)
    {
        $user = Agent::find($data['id']); //we send separate delete requests
        $user->delete();
        return new AgentResource($user);
    }

    public function delete(Request $data)
    {
        $user = Agent::whereIn('id', $data['ids'])->delete();
        return "ok";
    }

    public function create(Request $data)
    {

        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:agents,id,' . $data['id'],
            'name'   => 'required',
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        $agent                     = Agent::firstOrNew(['id' => $data['id']]);
        $agent->name               = $data['name'];
        $agent->commission         = $data['commission'] ?? 0;
        $agent->company_id         = $data['company_id'];
        $agent->brand_id           = $data['brand_id'];
        $agent->booking_source_id  = $data['booking_source_id'];
        $agent->parent_agent_id    = $data['parent_agent_id'];
        $agent->program_id         = $data['program_id'];
        $agent->comments           = $data['notes'];
        $agent->save();
        $agent->addContacts();//needed in merging for v1
        $agent->addDocuments();//needed in merging for v1

        $agent->sub_contacts()->detach();
        if ($data['sub_contacts']) {
            foreach ($data['sub_contacts'] as $contact_id) {
                $agent->sub_contacts()->attach($contact_id);
            }
        }

        $agents = Agent::where('parent_agent_id', $agent->id)->get();
        foreach ($agents as $sub_agent) {
            $sub_agent->parent_agent_id = null;
            $sub_agent->save();
        }

        if ($data['agents']) {
            foreach ($data['agents'] as $agent_id) {
                $sub_agent = Agent::find($agent_id);
                $sub_agent->parent_agent_id = $agent->id;
                $sub_agent->save();
            }
        }

        if ($data->ajax()) {
            $agent->account_id = $agent->id;
            $agent->model      = Agent::class;
            return response()->json($agent);
        }

        return redirect()->back();
    }



    public function update_store_api(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'id'   => 'nullable|unique:agents,id,' . $data['id'],
            'name'   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }
        $agent                     = Agent::firstOrNew(['id' => $data['id']]);
        $agent->name               = $data['name'];
        $agent->commission         = $data['commission'] ?? 0;

        if (is_array($data['company_id'])) {
            $agent->company_id         = (int)$data['company_id.id'];
        } else {
            $agent->company_id         = $data['company_id'];
        }


        if (is_array($data['brand_id'])) {
            $agent->brand_id           = (int)$data['brand_id.id'];
        } else {
            $agent->brand_id           = $data['brand_id'];
        }

        if (is_array($data['booking_source_id'])) {
            $agent->booking_source_id  = (int)$data['booking_source_id.id'];
        } else {
            $agent->booking_source_id  = $data['booking_source_id'];
        }

        $agent->program_id         = $data['program_id'];
        $agent->comments           = $data['comments'];
        $agent->save();

        $agent->documents()->sync($data['documents']);

        $agent->sub_contacts()->sync($data['sub_contacts'] ?? []);

        $agents = Agent::where('parent_agent_id', $agent->id)->whereNotIn('id', $data['sub_agents'] ?? [])->get();
        foreach ($agents as $sub_agent) {
            $sub_agent->parent_agent_id = null;
            $sub_agent->save();
        }

        if ($data['sub_agents']) {
            foreach ($data['sub_agents'] as $agent_id) {
                $sub_agent = Agent::find($agent_id);
                $sub_agent->parent_agent_id = $agent->id;
                $sub_agent->save();
            }
        }

        if ($data->ajax()) {
            $agent->account_id = $agent->id;
            $agent->model      = Agent::class;
            return response()->json($agent);
        }

        return new AgentResource($agent);
    }


    public function search_model(Request $data)
    {
        $mystring = $data['search'];
        $source = $data['booking_source'];
        $except = $data['except'];
        $agents  = Agent::where(function ($q) use ($mystring) {
            $q->where('id', $mystring)
                ->orWhere('name', 'like', "%" . $mystring . "%")
                ->orwhere('comments', 'like', "%" . $mystring . "%");
        });
        if ($source) {
            $agents = $agents->whereHas('booking_source', function ($q) use ($source) {
                $q->where('id', $source);
            });
        }
        if ($except) {
            $agents = $agents->where('id', '!=', $except);
        }

        return $agents;
    }

    public function search(Request $request, $lng)
    {
        $agents = $this->search_model($request);

        return view('agents.preview', compact(['agents', 'lng']));
    }


    public function search_agent_ajax(Request $data)
    {
        $agents = $this->search_model($data);

        $agents = $agents->with('booking_source')->take(Cookie::get('pages') ?? 10)->get();
        return response()->json($agents);
    }


    public function search_subaccount_with_agent_ajax(Request $data)
    {
        $agent = $data['parent_agent'];

        $agents = $this->search_model($data);
        $contacts = (new ContactController)->searchModel($data);

        if ($agent) {
            $agent = Agent::find($agent);
            $agents = $agent->agents()->whereIn('id', $agents->pluck('id'));
            $contacts = $agent->sub_contacts()->whereIn('contacts.id', $contacts->pluck('id'));
        } else {
            $agents = $agents->whereNotNull('parent_agent_id');
            $contacts = $contacts->whereHas('agent_account');
        }

        $agents = $agents->paginate(Cookie::get('pages') ?? 5);
        $contacts = $contacts->paginate(Cookie::get('pages') ?? 5);

        $subaccount = $agents->getCollection()->concat($contacts->getCollection());

        SubAccount::withoutWrapping();
        return SubAccount::collection($subaccount);
    }



    public function search_subaccount_with_agent_ajax2(Request $request)
    {
        if ($request->type && $request->id && $request->type!=$request->id) {
            $subaccount = (new $request->type)->where('id', $request->id)->get();
            return SubAccountResource::collection($subaccount);
        }

        $agent = $request['parent_agent'];

        $agents = $this->search_model($request);
        $contacts = (new ContactController)->searchModel($request);

        if ($agent) {
            $agent = Agent::find($agent);
            $agents = $agent->agents()->whereIn('id', $agents->pluck('id'));
            $contacts = $agent->sub_contacts()->whereIn('contacts.id', $contacts->pluck('id'));
        } else {
            $agents = $agents->whereNotNull('parent_agent_id');
            $contacts = $contacts->whereHas('agent_account');
        }

        $agents = $agents->filter($request)->paginate(Cookie::get('pages') ?? 5);
        $contacts = $contacts->filter($request)->paginate(Cookie::get('pages') ?? 5);

        $subaccount = $agents->getCollection()->concat($contacts->getCollection());

        return SubAccountResource::collection($subaccount);
    }

    public function search_subaccount_ajax(Request $data)
    {
        $agents = $this->search_model($data);
        $contacts = (new ContactController)->searchModel($data);

        $agents = $agents->paginate(Cookie::get('pages') ?? 5);
        $contacts = $contacts->paginate(Cookie::get('pages') ?? 5);
        $subaccount = $agents->concat($contacts);

        SubAccount::withoutWrapping();
        return SubAccount::collection($subaccount);
    }
}