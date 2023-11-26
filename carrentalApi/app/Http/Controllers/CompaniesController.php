<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;
class CompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function preview(Request $data, $lng)
    {
        $companies = $this->searchModel($data);

        $companies = $companies->filter($data)->paginate(Cookie::get('pages') ?? '5');
        return view('companies.preview', compact(['companies', 'lng']));
    }


    public function preview_api(Request $request, $lng=null)
    {
        $companies = $this->searchModel($request);
        $companies = $companies->filter($request);
        return new CompanyCollection($companies->paginate($request->get('per_page') ?? '5'), ['*'], 'page', $request->get('page'));
    }

    public function edit(Request $request, $id) {
        $company = Company::find($id);

        return new CompanyResource($company);
    }

    public function searchModel(Request $data) {
        $mystring = $data['search'];
        $rentals = $data['rentals'];

        $companies = Company::orderBy('created_at', 'desc')->where(function ($q) use ($mystring) {
            $q->where('name', 'like', "%" . $mystring . "%")
            ->orWhere('title', 'like', "%" . $mystring . "%")
            ->orwhere('id', $mystring)
            ->orwhere('afm', 'like', "%" . $mystring . "%")
            ->orWhere('doy', 'like', "%" . $mystring . "%")
            ->orwhere('phone', 'like', "%" . $mystring . "%");
        });
        if ($rentals) {
            $companies = $companies->whereHas('rentals', function($q) use ($rentals) {
                $q->where('rentals.id', $rentals);
            });
        }
        return $companies;
    }

    public function search_ajax(Request $data){
        $companies = $this->searchModel($data);

        $companies = $companies->take(Cookie::get('pages') ?? 10)->get();
        return $companies;
    }

    public function searchData(Request $data, $lng){
        $mystring        = $data['search'];
        $exist           = Company::whereHas('drivers', function ($q) use ($data) {
                                $q->where('id', $data['driver']);
                            })->pluck('id');
        $drivers        = Company::where('companies.name', 'like', "%" . $mystring . "%")
                        ->whereNotIn('id', $exist)
                        ->orWhere('companies.afm', 'like', "%" . $mystring . "%")
                        ->whereNotIn('id', $exist)
                        ->take(5)
                        ->get();
        return $drivers;
    }

    public function create_view(Request $data, $lng)
    {
        if (isset($data['cat_id'])) {
            return view('companies.create', [
                'lang'           => Language::all(),
                'company' => Company::find($data['cat_id']),
                'lng'            => $lng
            ]);
        }
        return view('companies.create', ['lang' => Language::all(), 'lng' => $lng]);
    }

    public function delete(Request $data)
    {
        $users = Company::whereIn('id', $data['ids'])->delete();
        return "company deleted";
    }

    public function delete_api(Request $data)
    {
        $com = Company::find($data['id']);
        $com->delete();
        return new CompanyResource($com);
    }


    public function update_store_api(Request $data){
        $id= $data['id'];
        $validator = Validator::make($data->all(), [
            'id'     => 'nullable|numeric|exists:companies',
            'name'   => 'required',
            'afm'    => "required|unique:companies,afm,$id",
            'doy'    => 'nullable',
            'country' => 'nullable',
            'city'   => 'nullable',
            'job'    => 'nullable',
            'phone'  => 'nullable',
            'email'  => 'nullable',
            'website' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        if (isset($data['id']) && $data['id'] != '') {

            if($data['main']) {
                $main_company = Company::where('main', 1)->first();
                if ($main_company) {
                    $main_company->main = 0;
                    $main_company->save();
                }
            }

            $update_company = Company::updateOrCreate(['id' => $data['id']],
                [
                    'name'      => $data['name'],
                    'title'     => $data['title'],
                    'afm'       => $data['afm'],
                    'doy'       => $data['doy'],
                    'country'   => $data['country'],
                    'city'      => $data['city'],
                    'job'       => $data['job'],
                    'phone'     => $data['phone'],
                    'phone_2'   => $data['phone_2'],
                    'email'     => $data['email'],
                    'website'   => $data['website'],
                    'address'   => $data['address'],
                    'zip_code'  => $data['zip_code'],
                    'comments'  => $data['comments'],
                    'main'      => $data['main'] == 'on' ? 1 : 0,
                    'mite_number' => $data['mite_number'],
                    'foreign_afm' => $data->has('foreign_afm') && $data->foreign_afm == true
                ]);

            return new CompanyResource($update_company);
        }

        if($data['main']) {
            $main_company = Company::where('main', 1)->first();
            if ($main_company) {
                $main_company->main = 0;
                $main_company->save();
            }
        }

        $new_company = Company::create([
            'name'      => $data['name'],
            'title'     => $data['title'],
            'afm'       => $data['afm'],
            'doy'       => $data['doy'],
            'country'   => $data['country'],
            'city'      => $data['city'],
            'job'       => $data['job'],
            'phone'     => $data['phone'],
            'phone_2'   => $data['phone_2'],
            'email'     => $data['email'],
            'website'   => $data['website'],
            'address'   => $data['address'],
            'zip_code'  => $data['zip_code'],
            'comments'  => $data['comments'],
            'main'      => $data['main'] == 'on' ? 1 : 0,
            'mite_number' => $data['mite_number'],
            'foreign_afm' => $data->has('foreign_afm') && $data->foreign_afm == true
        ]);

        return new CompanyResource($new_company);
    }

    public function create(Request $data)
    {
        if (isset($data['id']) && $data['id'] != '')
            Session::flash('message', __('Ενημερώθηκε με επιτυχία.'));
        else
            Session::flash('message', __('Δημιουργήθηκε με επιτυχία.'));
        Session::flash('alert-class', 'alert-success');

        $validator = Validator::make($data->all(), [
            'id'     => 'nullable|numeric|exists:companies',
            'name'   => 'required',
            'afm'    => 'required',
            'doy'    => 'nullable',
            'country'=> 'nullable',
            'city'   => 'nullable',
            'job'    => 'nullable',
            'phone'  => 'nullable',
            'email'  => 'nullable',
            'website'=> 'nullable',
        ]);

        if ($validator->fails()) {
            if ($data->ajax()) {
                return response()->json($validator->errors()->first(), 400);
            }
            Session::flash('message', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back()->withInput();
        }

        // dd($data['main'] == 'on' ? 1 : 0);

        if (isset($data['id']) && $data['id'] != '') {
            if($data['main']) {
                $main_company = Company::where('main', 1)->first();
                if ($main_company) {
                    $main_company->main = 0;
                    $main_company->save();
                    $main_company->handleDrivers();
                }
            }

            $update_company = Company::updateOrCreate(['id' => $data['id']],
                [
                    'name'      => $data['name'],
                    'title'     => $data['title'],
                    'afm'       => $data['afm'],
                    'doy'       => $data['doy'],
                    'country'   => $data['country'],
                    'city'      => $data['city'],
                    'job'       => $data['job'],
                    'phone'     => $data['phone'],
                    'phone_2'   => $data['phone_2'],
                    'email'     => $data['email'],
                    'website'   => $data['website'],
                    'address'   => $data['address'],
                    'zip_code'  => $data['zip_code'],
                    'comments'  => $data['comments'],
                    'main'      => $data['main'] == 'on' ? 1 : 0,
                    'mite_number' => $data['mite_number'],
                    'foreign_afm' => $data->has('foreign_afm') && $data->foreign_afm == true
                ]);
            $update_company->handleDrivers();
            if ($data->ajax()) {
                return response()->json($update_company);
            }


            return redirect()->back();
        }

        if($data['main']) {
            $main_company = Company::where('main', 1)->first();
            if ($main_company) {
                $main_company->main = 0;
                $main_company->save();
                $main_company->handleDrivers();
            }
        }

        $new_company = Company::create([
            'name'      => $data['name'],
            'title'     => $data['title'],
            'afm'       => $data['afm'],
            'doy'       => $data['doy'],
            'country'   => $data['country'],
            'city'      => $data['city'],
            'job'       => $data['job'],
            'phone'     => $data['phone'],
            'phone_2'   => $data['phone_2'],
            'email'     => $data['email'],
            'website'   => $data['website'],
            'address'   => $data['address'],
            'zip_code'  => $data['zip_code'],
            'comments'  => $data['comments'],
            'main'      => $data['main'] == 'on' ? 1 : 0,
            'mite_number' => $data['mite_number'],
            'foreign_afm' => $data->has('foreign_afm') && $data->foreign_afm == true
        ]);
        $new_company->handleDrivers();
        if ($data->ajax()) {
            return response()->json($new_company);
        }

        return redirect()->back();
    }
}