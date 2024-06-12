<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_transfer_order_id',
        'sto_no',
        'quantity',
        'product_id',
        'last_grn_quantity',
        'scanned_req_quantity',
        'last_scanned_req_quantity'
    ];

    protected $appends = [ 'product_name' ];

    public function getProductNameAttribute(){
        $product = Product::find( $this->product_id );
        return ( $product ) ? $product->name : "";
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

}
