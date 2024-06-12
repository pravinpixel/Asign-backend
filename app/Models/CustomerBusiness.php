<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBusiness extends Model
{
    use HasFactory;
    public $timestamps = false;
     public function business()
    {
        return $this->hasOne(Customer::class,'id','customer_business_id');
    }
}
