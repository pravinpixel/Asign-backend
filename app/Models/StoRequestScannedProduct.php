<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoRequestScannedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sto_id',
        'sto_product_id',
        'scanned_req_po_id',
        'category',
        'quantity'
    ];
}
