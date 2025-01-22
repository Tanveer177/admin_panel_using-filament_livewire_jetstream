<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    //
    public function showNavmenu() {
        $menus = DB::table('navmenus')->get();
        // dd($menus);
        $data['menus'] = $menus;
        return view('home', $data);
    }

}
