<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerActivityLog extends Model
{
    use HasFactory;
    public function customer()
    {
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','tag_id');
    }

    public function updateTimestamps()
    {
        $this->timestamps = false;
    }
}
