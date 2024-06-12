<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'po_no',
        'quantity',
        'product_id',
        'grn_quantity',
        'last_grn_quantity',
        'status'
    ];

    protected $appends = [ 'product_name' ];

    public function products()
    {
        return $this->hasOne( Product::class, 'id', 'product_id' );
    }

    public function scannedProducts(){
        return $this->hasMany( PurchaseOrderGrnScannedProduct::class, 'purchase_order_product_id', 'id' );
    }

    public function getProductNameAttribute(){
        $product = Product::find( $this->product_id );
        return ( $product ) ? $product->name : "";
    }

    public function grn(){
        return $this->hasMany( Grn::class, 'purchase_order_id', 'id' );
    }

    public function grn_relation(){
        return $this->hasMany(GrnRelation::class, 'purchase_order_product_id', 'id');
    }

}
