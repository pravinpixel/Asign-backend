<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'label_order_id',
        'product_id',
        'qty'
    ];

    public function products(){
        return $this->belongsTo( Product::class, 'product_id')->withTrashed();

    }
}
