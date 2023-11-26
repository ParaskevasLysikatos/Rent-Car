<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScannerController extends Controller
{
    public  function index($lng){
        return view('scanner.scanner', compact('lng'));
    }

    public function redirect(Request $data){
        if(isset($data['lng']))
            $lng =  $data['lng'];
        else
            $lng = 'el';

        if(!Auth::check()) {
            return redirect(config('ea.url'));
        }

        if(isset($data['car'])){
            return redirect()->route('create_visit_view', ['locale' => $lng, 'car' => $data['car']]);
        }


        return redirect()->route('homepage');
    }
}
