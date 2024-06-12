<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\RoleUserHasPermission;
use App\Models\User;
use DB;
class EnsureUserHasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public $user;
    public function handle(Request $request, Closure $next,$access_menu=null)
    {  
         $user= Auth::guard('admin')->user();
        if ($user->role_id==53) {
          return $next($request);
        } else {
        if (!empty($access_menu)) {
            $permission=Permission::where('name',$access_menu)->first();
           $info =RoleUserHasPermission::Where('permission_id',$permission->id)->where('role_id',$user->role_id)->where('user_id',$user->id)->first();
           if(!empty($info)){
             return $next($request);
           }
           return response()->view('403');
           
        }
      }
      return $next($request);
    }
}
