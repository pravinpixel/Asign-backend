<?php
namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasRoles,HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $appends = ['role_name','branch_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function location()
    {
        return $this->belongsTo(BranchLocation::class, 'branch_office_id')->withTrashed();
    }
    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

    public function getnotificationsAttribute()
    {
        $notification=Notification::where('is_read',0)->take(5)->orderBy('id','desc')->get();
        if($notification){
            return $notification;
        }

        return "";
    }
     public function getcountAttribute()
    {
        $count=Notification::where('is_read',0)->get()->count();
        if($count){
            return $count;
        }

        return 0;
    }

    protected function ProfileImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value==NUll) ? NUll : config('app.image_url').$value,
        );
    }
    public function getRoleNameAttribute()
    {
        $role=Role::where("id", $this->role_id)->first();
        if($role){
            return $role->name;
        }

        return "";
    }
    public function getBranchNameAttribute()
    {
        $branch_location=BranchLocation::where("id", $this->branch_office_id)->first();
        if($branch_location){
            return $branch_location->location;
        }

        return "";
    }

    public function branchLocations() {
        return $this->hasMany(BranchLocation::class, 'id','branch_office_id')->withTrashed();
    }

    public function dropdownroles()
    {
        return $this->hasMany(Role::class,'id', 'role_id');
    }


}
