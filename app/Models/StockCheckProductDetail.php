<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCheckProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_check_id', 'product_id', 'code', 'envelope', 'category'
    ];

    public $timestamps = false;

    public function stockCheck()
    {
        return $this->belongsTo(StockCheck::class, 'stock_check_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select('id', 'name');
    }

}
