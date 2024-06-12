<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsignProtectController extends Controller
{
    public function index(Request $request)
    {

        return view('pages.asign_protect.index');
    }

    public function asignRequest(Request $request)
    {

        return view('pages.asign_protect.request');
    }
}
