<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderGrnScannedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_id',
        'purchase_order_product_id',
        'scanned_product_id',
        'category',
        'quantity'
    ];
}
