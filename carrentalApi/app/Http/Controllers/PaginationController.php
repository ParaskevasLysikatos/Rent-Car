<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Http\Request;

class PaginationController extends Controller
{
    public function pages(Request $data){

        Cookie::queue('pages', $data->pages, 60*24*30*365);
        return redirect()->back()->withInput();

    }
}
