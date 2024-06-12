<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
class Role extends SpatieRole
{
    use HasFactory;
     protected $casts = [
        'permission' => 'array',
    ];
    protected $appends = ['user_count'];
    public function getUserCountAttribute()
    { 
        $user=User::where("role_id", $this->id)->get()->count();
        if($user>0){
            return $user;
        }

        return 0;
    }
}
