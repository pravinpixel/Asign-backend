<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\RoleUserHasPermission;
class RoleManagementController extends Controller
{
  protected $pageNumber = 1;
  protected $perPage = 10;
  public function index(Request $request)
  {
      return view("pages.masters.role.index", []);
  }
  public function list(Request $request)
  {
      if ($request->has("page")) {
          $this->pageNumber = $request->get("page");
      }
      if ($request->has("per_page")) {
          $this->perPage = $request->get("per_page");
      }
      try {
          $search = $request->input("search");
          $search_key = $request->input("search_key");
          $sort = $request->input('sort') ?? 'name|asc';
          if ($sort)
              $sort = explode('|', $sort);
          $field_sort = $request->input("field_sort");
          $query = Role::query();
          if (!empty($search) && !empty($search_key)) {
              if ($search_key == "all") {
                  $query
                      ->where("name", "like", "%" . $search . "%");
              } else {
                  $query->where($search_key, "like", "%" . $search . "%");
              }
          }
          $query->where('id','!=',53);
          $query->orderBy($sort[0], $sort[1]);
          $data = $query->paginate(
              $this->perPage,
              ["*"],
              "page",
              $this->pageNumber
          );
          return $this->returnSuccess($data);
      } catch (\Exception $e) {
          return $this->returnError($e->getMessage());
      }
  }
  public function create(Request $request)
  {   
      $all_permissions  = Permission::all();
      $permission_groups = User::getpermissionGroups();
      return view("pages.masters.role.add_edit", ['permission_groups'=>$permission_groups,'all_permissions'=>$all_permissions]);
  }
   public function edit($id)
  {   
      $all_permissions  = Permission::all();
      $permission_groups = User::getpermissionGroups();
      $role = Role::find($id);
      return view("pages.masters.role.add_edit", ['permission_groups'=>$permission_groups,'all_permissions'=>$all_permissions,'role'=>$role]);
  }
  public function check(Request $request)
  {
    $id=$request->id;
    $validatedData = $request->validate([
        'role_name' => 'required|unique:roles,name,'.$id,
        'role_name.unique' => ' This Role name Already exist.',
        'role_name.required' => ' This Role name field is required.',       
    ]);
      try {
          return $this->returnSuccess(true);
      } catch (\Exception $e) {
          return $this->returnError($e->getMessage());
      }
  }
  public function save(Request $request)
  {   
    $id=$request->id??NULL;
    $validatedData = $request->validate([
        'role_name' => 'required|unique:roles,name,'.$id,
        'role_name.unique' => ' This Role name Already exist.',
        'role_name.required' => ' This Role name field is required.',       
     ]);
      try{
        $permissions = $request->input('permissions');
          if(isset($id)){
          $role =Role::find($id);
          $role->name = $request->input('role_name');
          $role->update();
          if($permissions){
             $this->asignRolePermission($permissions,$id);
          }
          $role->syncPermissions($permissions);
          return $this->returnSuccess($role,"Role updated successfully");
          }else{
         
          $role = new Role;
          $role->name = $request->input('role_name');
          $role->save();

          if (!empty($permissions)) {
            $role->syncPermissions($permissions);
          }
          return $this->returnSuccess($role,"Role created successfully");
          }
      } catch (\Exception $e) {
          return $this->returnError($e->getMessage());
      }

    }
   public function asignRolePermission($permission,$role_id)
  {     
      $new_permission=Permission::whereIN('name',$permission)->pluck('id')->toArray();
      $current_permission=RoleHasPermission::where('role_id',$role_id)->select('permission_id as id')->pluck('id')->toArray();
      $added_datas=array_diff($new_permission, $current_permission);
      $removed_datas=array_diff($current_permission, $new_permission);

      $users=User::where('role_id',$role_id)->get();
      try{
        if(count($removed_datas)>0){
          foreach($removed_datas as $removed_data){
            $role_has_permission=RoleUserHasPermission::where('permission_id',$removed_data)->where('role_id',$role_id)->delete();
          }
        }
        if(count($added_datas)>0){
          foreach($added_datas as $added_data){
           foreach($users as $user){
              $ins['permission_id']=$added_data;
              $ins['role_id']=$role_id;
              $ins['user_id']=$user->id;
           RoleUserHasPermission::updateOrCreate(['permission_id'=>$added_data,'role_id'=>$role_id,'user_id'=>$user->id],$ins);
           }
          }
        }
      
      } catch (\Exception $e) {
          return $this->returnError($e->getMessage());
      }
       return true;
  }
    


}
