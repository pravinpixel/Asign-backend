<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Grn extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'stock_order_id',
        'type',
        'grn_quantity',
        'grn_no',
        'branch_location_id',
        'manufacturer_id',
        'transporter_id',
        'created_on',
        'created_by'
    ];

    protected $appends = ['user_name', 'po_no', 'sto_no'];

    public function setCreatedOnAttribute($value)
    {

        $this->attributes['created_on'] = Carbon::createFromFormat('l, d M, Y', $value)->toDateString();
    }

    public function getPoNoAttribute($value)
    {

        $po = PurchaseOrder::find($this->purchase_order_id);
        return ($po) ? $po->purchase_order_no : "";
    }

    public function getStoNoAttribute($value)
    {

        $sto = StockTransferOrder::find($this->purchase_order_id);
        return ($sto) ? $sto->sto_no : "";
    }

    public function grnScannedProducts()
    {

        return $this->hasMany(PurchaseOrderGrnScannedProduct::class, 'grn_id', 'id');
    }

    public function purchaseOrder()
    {

        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_order_id');
    }

    public static function grnNoGenerator()
    {

        $maxNO = DB::select("select max(cast(substring(grn_no,4) as signed)) as max_purchase_no from as_grns");
        if ($maxNO[0]->max_purchase_no) {
            $filterNum = $maxNO[0]->max_purchase_no;
            $filterNum++;
            $filterNumLength = strlen($filterNum);
            $clearNumber = str_pad($filterNum, $filterNumLength + 5, '0', STR_PAD_LEFT);
            $pur_order_no = "GRN" . $clearNumber;
        } else {
            $filterNum = 1;
            $pur_order_no = "GRN00000" . $filterNum;
        }
        return $pur_order_no;
    }

    public function branch_location()
    {

        return $this->belongsTo(BranchLocation::class, 'branch_location_id')->withTrashed();
    }

    public function sto_sender_name()
    {

        return $this->belongsTo(BranchLocation::class, 'manufacturer_id')->withTrashed();
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id')->withTrashed();
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class, 'transporter_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUserNameAttribute()
    {
        $user = User::find($this->created_by);
        return ($user) ? $user->name : "";
    }

    public function sto_products()
    {
        return $this->hasMany(StockTransferOrderProduct::class, 'stock_transfer_order_id', 'purchase_order_id');
    }

    public function po_products()
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id', 'purchase_order_id');
    }

    public function grn_relations()
    {
        return $this->hasMany(GrnRelation::class, 'grn_id', 'id');
    }

    public function stockTransferOrder()
    {
        return $this->hasOne(StockTransferOrder::class, 'id', 'purchase_order_id');
    }

    public function grnProductDetails()
    {
        return $this->hasMany(GrnProductDetail::class);
    }
}
