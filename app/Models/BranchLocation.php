<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchLocation extends Model
{
    use HasFactory,SoftDeletes;
    // protected $table = 'branch_locations';

    public function purchaseOrders(){
        return $this->hasMany( PurchaseOrder::class, 'delivery_location' );
    }
}
