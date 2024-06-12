<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use HasFactory,SoftDeletes;

    public function purchaseOrders(){
        return $this->hasMany( PurchaseOrder::class, 'manufacturer_name' );
    }
}
