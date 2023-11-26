<?php

namespace App\Http\Controllers;

use App\Http\Resources\LicencePlateResource;
use App\Language;
use App\LicencePlate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;

class LicencePlateController extends Controller
{
    // public function __construct()
    // {
    //     // $this->middleware('auth');
    // }



    // public function edit(Request $request, $id)
    // {
    //     $licencePlate = LicencePlate::find($id);

    //     return new LicencePlateResource($licencePlate);
    // }

    // public function updateFromRequest(Request $request, LicencePlate $licencePlate)
    // {
    //    // $licencePlate->licence_plate= $request['licence_plate'];
    //    // $licencePlate->vehicle_id   = $request['vehicle_id'];
    //     $licencePlate->registration_date= $request['registration_date'];
    //     $licencePlate->save();

    //     return $licencePlate;
    // }

    // public function store(Request $request)
    // {
    //     $licencePlate = new LicencePlate();
    //     $this->updateFromRequest($request, $licencePlate);

    //     return new LicencePlateResource($licencePlate);
    // }

    // public function update(Request $request, $id)
    // {
    //     $licencePlate = LicencePlate::findOrFail($id);
    //     $this->updateFromRequest($request, $licencePlate);


    //     return new LicencePlateResource($licencePlate);
    // }


    // public function delete(Request $request, $id = null)
    // {
    //     if ($request->wantsJson()) {
    //         $licencePlate = LicencePlate::find($id)->delete();
    //         return response()->json();
    //     }
    //    return 0;
    // }


}