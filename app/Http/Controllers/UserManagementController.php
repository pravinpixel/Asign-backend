<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\City;
use App\Models\BranchLocation;
use Illuminate\Validation\Rule;
use App\Models\RoleUserHasPermission;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Crypt;

class UserManagementController extends Controller
{
    protected $pageNumber = 1;
    protected $perPage = 10;

    public function code()
    {
        $code = 12301;
        $user = User::whereNotNull('code')->get();
        if (count($user) > 0) {
            $user = User::latest()->first();
            $code = $user->code + 1;
        }
        return $code;
    }

  public function index(Request $request)
  {
      $locations=BranchLocation::all();
      $roles=Role::all();
      return view("pages.user-management.index", ['locations' => $locations,'roles'=>$roles]);
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
          $location = $request->input("location");
          $role = $request->input("role");
          $search_key = $request->input("search_key");
          $sort = $request->input("sort") ?? "asc";
          $field_sort = $request->input("field_sort");
          $sort = $request->input('sort') ?? 'name|asc';
          if ($sort)
              $sort = explode('|', $sort);
          $query = User::query()->with('branchLocations','dropdownroles');
          if (!empty($search) && !empty($search_key)) {
              if ($search_key == "all") {
                  $query
                      ->where("name", "like", "%" . $search . "%")
                      ->orwhere("email", "like", "%" . $search . "%")
                      ->orwhere("mobile_number", "like", "%" . $search . "%")
                      ->orwhere("code", "like", "%" . $search . "%");
              } else {
                  $query->where($search_key, "like", "%" . $search . "%");
              }
          }
          
          if ($location){
            $locationData = explode(',', $location);
            $query->whereHas('branchLocations', function($query) use ($locationData) {
                $query->whereIn('location', $locationData);
            });
        }
        if ($role){
            $roleData = explode(',', $role);
            $query->whereHas('dropdownroles', function($query) use ($roleData) {
                $query->whereIn('name', $roleData);
            });
        }
          $query->where('role_id','!=',53)->orderBy($sort[0], $sort[1]);
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
      $roles=Role::where('id','!=',53)->orderBy('name','asc')->get();
      $cities=City::where('is_serviceable',1)->orderBy('name','asc')->get();
      $locations=BranchLocation::orderBy('location','asc')->get();
      return view("pages.user-management.add_edit", ['permission_groups'=>$permission_groups,'all_permissions'=>$all_permissions,'roles'=>$roles,'cities'=>$cities,'locations'=>$locations]);
  }
   public function edit($id)
  {   
      $all_permissions  = Permission::all();
      $permission_groups = User::getpermissionGroups();
      $user = User::find($id);
      $roles=Role::where('id','!=',53)->orderBy('name','asc')->get();
      $cities=City::where('is_serviceable',1)->orderBy('name','asc')->get();
      $locations=BranchLocation::orderBy('location','asc')->get();
      return view("pages.user-management.add_edit", ['permission_groups'=>$permission_groups,'all_permissions'=>$all_permissions,'user'=>$user,'roles'=>$roles,'cities'=>$cities,'locations'=>$locations]);
  }
  
 

    public function check(Request $request)
    {
        $id = $request->id ?? null;

        $rule_arr = [
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id,deleted_at,NULL',
            'mobile_number' => 'required|digits:10',
            'role_id' => 'required|exists:roles,id',
            'branch_office_id' => 'required|exists:branch_locations,id',
        ];

        if ($id == null || !empty($request->input('retype_password'))){
            $rule_arr['password'] = 'required|min:6';
            $rule_arr['retype_password'] = 'required|same:password|min:6';
        }
        $validatedData = $request->validate($rule_arr);

        try {
            return $this->returnSuccess(true);
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function save(Request $request)
    {
        try {
            $id = $request->id;
            $permissions = $request->input('permissions');
            if (isset($id)) {
                $user = User::find($id);
                $user->name = $request->input('user_name');
                $user->mobile_number = $request->input('mobile_number');
                $user->email = $request->input('email');
                $user->status = 1;
                $user->code = $user->code;
                $user->city_access = ($request->input('city_access') == '') ? NULL : implode(',', $request->input('city_access'));
                $user->branch_office_id = $request->input('branch_office_id');

                if ($request->input('retype_password')) {
                    $retype_password = $request->input('retype_password');
                    $password = $request->input('password');
                    if ($retype_password !== $password) {
                        return $this->returnError("Password and Retype Password does not match");
                    }
                    $user->password = bcrypt($request->input('password'));
                    $user->hash_password= Crypt::encryptString($request->input('password'));
                }

                $user->role_id = $request->input('role_id');
                $user->update();
                $user->roles()->detach();

                if ($request->input('role_id')) {
                    $user->assignRole($request->input('role_id'));
                }
                if ($request->input('permissions')) {
                    $this->asignPermission($request->input('permissions'), $user);
                }
                return $this->returnSuccess($user, "User updated successfully");
            } else {

                $user = new User();
                $user->name = $request->input('user_name');
                $user->mobile_number = $request->input('mobile_number');
                $user->email = $request->input('email');
                $user->status = 1;
                $user->code = $this->code();
                $user->password = bcrypt($request->input('password'));
                $user->hash_password= Crypt::encryptString($request->input('password'));
                $user->city_access = ($request->input('city_access') == '') ? NULL : implode(',', $request->input('city_access'));
                $user->branch_office_id = $request->input('branch_office_id');
                $user->role_id = $request->input('role_id');
                $user->save();
                if ($request->input('role_id')) {
                    $user->assignRole($request->input('role_id'));
                }
                if ($request->input('permissions')) {
                    $this->asignPermission($request->input('permissions'), $user);
                }
                return $this->returnSuccess($user, "User created successfully");
            }

        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }

    }

    public function permission(Request $request)
    {
        try {
            $permissions = [];
            if ($request->user == '') {
                $role = Role::find($request->role);
                $permissions = ($role == null) ? [] : $role->permissions;
            } else {
                $user = User::find($request->user);
                if ($user->role_id != $request->role) {
                    $role = Role::find($request->role);
                    $permissions = ($role == null) ? [] : $role->permissions;
                } else {
                    $permission_value = RoleUserHasPermission::where('user_id', $request->user)->select('permission_id')->get();
                    $permissions = Permission::whereIn('id', $permission_value)->get();
                }

            }
            return $this->returnSuccess($permissions, "");
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

    public function asignPermission($permission, $user)
    {
        RoleUserHasPermission::where('user_id', $user->id)->delete();
        try {
            if (count($permission) > 0) {
                foreach ($permission as $permission_data) {
                    $access = Permission::where('name', $permission_data)->first();
                    if ($access) {

                        $has_permission = new RoleUserHasPermission();
                        $has_permission->role_id = $user->role_id;
                        $has_permission->permission_id = $access->id;
                        $has_permission->user_id = $user->id;
                        $has_permission->save();
                    }
                }
            }

        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
        return true;
    }

    public function delete($id)
    {
        try {
            RoleUserHasPermission::where('user_id', $id)->delete();
            $user = User::find($id)->delete();
            return $this->returnSuccess([], "User deleted successfully");
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
}
