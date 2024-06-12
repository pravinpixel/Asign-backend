<?php

namespace App\Http\Controllers;
use App\Models\Medium;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Gallery;
use App\Models\Exhibition;
use App\Models\User;
use App\Models\Role;
use App\Models\ThirdParty;
use App\Models\Permission;
use App\Models\CustomerArtist;
use App\Models\Customer;




class DashboardController extends Controller
{
    public function index(Request $request)
    {
    
       
         return view('pages.dashboard',
         []);
    }
    public function get(Request $request)
    {
        
    }
  
    public function save(Request $request)
    {

    }
    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }

}
