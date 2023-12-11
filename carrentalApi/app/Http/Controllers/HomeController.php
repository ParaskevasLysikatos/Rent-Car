<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Company;
use App\CompanyPreferences;
use App\Location;
use App\Place;
use App\Station;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($lng)
    {
        $brand = Brand::get('id')->count() > 0;
        $location = Location::get('id')->count() > 0;
        $station = Station::get('id')->count() > 0;
        $place = Place::get('id')->count() > 0;
        $company = Company::get('id')->count() > 0;

        return view('home', compact([
            'brand', 'location', 'station', 'place', 'company', 'lng'
        ]));
    }

    public function index_api(Request $request){
        $brand = Brand::get('id')->count() > 0;
        $location = Location::get('id')->count() > 0;
        $station = Station::get('id')->count() > 0;
        $place = Place::get('id')->count() > 0;
        $company = CompanyPreferences::get('id')->count() > 0;
           return response()->json([
            "brand" => $brand, "location" => $location,
            "station" => $station, "place" => $place,'MyCompany'=>$company
        ]);

    }

}