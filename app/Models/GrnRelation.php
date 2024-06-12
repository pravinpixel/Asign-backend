<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnRelation extends Model
{
    use HasFactory;

    protected $appends = [ 'grn_no', 'product_name', 'sto_product_name' ];

    protected $fillable = [
        'grn_id',
        'purchase_order_id',
        'sto_id',
        'purchase_order_product_id',
        'sto_product_id',
        'product_grn_quantity',
    ];

    public function getGrnNoAttribute(){
        $grnData = Grn::find($this->grn_id);
        return ($grnData) ? $grnData->grn_no : "";
    }

    public function getProductNameAttribute() {
        $poProduct = PurchaseOrderProduct::find($this->purchase_order_product_id);

        if (!$poProduct) {
            return "";
        }

        $product = Product::find($poProduct->product_id);

        if (!$product) {
            // Handle the case where Product is not found
            return "";
        }

        return $product->name;
    }

    public function getStoProductNameAttribute() {
        $stoProduct = StockTransferOrderProduct::find($this->sto_product_id);

        if (!$stoProduct) {
            return "";
        }

        $product = Product::find($stoProduct->product_id);

        if (!$product) {
            // Handle the case where Product is not found
            return "";
        }

        return $product->name;
    }

}
