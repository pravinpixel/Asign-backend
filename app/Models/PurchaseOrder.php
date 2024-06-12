<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_no',
        'manufacturer_name',
        'delivery_location',
        'status',
        'order_date',
        'generated_by'
    ];

    public const STATUS = [
        'open' => ['label' => 'Authentication', 'color' => 'statusSkyblue', 'role' => '']
    ];

    protected $dates = ['order_date'];

    public function setOrderDateAttribute($value)
    {
        $this->attributes['order_date'] = Carbon::createFromFormat('l, d M, Y', $value)->toDateString();
    }

    public function branch_location(){

        return $this->belongsTo( BranchLocation::class, 'delivery_location' )->withTrashed();
    }

    public function manufacturer(){
        return $this->belongsTo( Manufacturer::class, 'manufacturer_name')->withTrashed();

    }

    public function purchaseOrderProducts(){
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id', 'id');
    }

    public function purchase_order_products(){
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'generated_by');
    }
}
