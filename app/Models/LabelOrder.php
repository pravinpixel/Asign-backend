<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'manufacturer_id',
        'delivery_location_id',
        'order_date'
        // 'status',
        // 'generated_by'
    ];

    protected $dates = ['order_date'];

    public function setOrderDateAttribute($value)
    {
        $this->attributes['order_date'] = Carbon::createFromFormat('l, d M, Y', $value)->toDateString();
    }

    public function label_order_details(){
        return $this->hasMany(LabelOrderDetail::class, 'label_order_id');
    }

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function location(){
        return $this->belongsTo(BranchLocation::class, 'delivery_location_id')->withTrashed();
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
