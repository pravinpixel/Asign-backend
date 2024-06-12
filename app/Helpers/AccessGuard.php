<?php

namespace App\Helpers;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\RoleUserHasPermission;
use App\Models\User;
use DB;
use Auth;
class AccessGuard
{
    public function hasAccess($route_name)
    {
        if (auth()->user()->role_id==53) {
            return true;
        } else {
        if (!empty($route_name)) {
            $user=auth::user();
            $permission=Permission::where('name',$route_name)->first();
        if($permission){
           $info =RoleUserHasPermission::Where('permission_id',$permission->id)->where('role_id',$user->role_id)->where('user_id',$user->id)->first();
           if(!empty($info)){
             return true;
           }
            return false;
        }
        return false;
           
        }
      }
    }
    public function checkRole($id,$group_name){
      $count_group=DB::table('roles')
        ->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
        ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->select(DB::raw('count(*) as count'))
        ->groupBy('roles.name', 'permissions.group_name')
        ->where('roles.id',$id)->where('permissions.group_name',$group_name)
        ->first();
           if($count_group){
                  return $count_group->count;
           }
           return 0;
    }
    public function checkUser($id,$group_name){
     $count_group=DB::table('users')
    ->join('role_user_has_permissions', 'users.id', '=', 'role_user_has_permissions.user_id')
    ->join('permissions', 'role_user_has_permissions.permission_id', '=', 'permissions.id')
    ->select(DB::raw('count(*) as count'))
    ->groupBy('permissions.group_name')
    ->where('users.id', $id)->where('permissions.group_name', $group_name)
    ->first();
           if($count_group){
                  return $count_group->count;
           }
           return 0;
    }

}
