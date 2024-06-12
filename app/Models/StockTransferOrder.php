<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockTransferOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'sto_no',
        'stock_destination_id',
        'stock_source_id',
        'transfer_reason_id',
        'transfer_reason',
        'created_date',
        'shipping_date',
        'tracking_id',
        'status'
    ];

    protected $dates = [
            'created_date',
            'shipping_date'
        ];

    public function setCreatedDateAttribute($value){
        $this->attributes['created_date'] = Carbon::createFromFormat('l, d M, Y', $value)->toDateString();
    }

    public function setShippingDateAttribute($value){
        $this->attributes['shipping_date'] = Carbon::createFromFormat('l, d M, Y', $value)->toDateString();

    }

    public function stockSource(){

        return $this->belongsTo( BranchLocation::class, 'stock_source_id' )->withTrashed();
    }

    public function stockDestination(){

        return $this->belongsTo( BranchLocation::class, 'stock_destination_id' )->withTrashed();
    }

    public function transfer_reasons(){
        return $this->belongsTo( TransferReason::class, 'transfer_reason_id' )->withTrashed();
    }

    public function stockTransferOrderProduct(){
        return $this->hasMany(StockTransferOrderProduct::class, 'stock_transfer_order_id', 'id');
    }

    public function stock_transfer_order_products(){
        return $this->hasMany(StockTransferOrderProduct::class, 'stock_transfer_order_id', 'id');
    }

    public static function stoNoGenerator()
    {
        $maxNO = DB::select("select max(cast(substring(sto_no,4) as signed)) as max_sto_no from as_stock_transfer_orders");
        if ($maxNO[0]->max_sto_no) {
            $filterNum = $maxNO[0]->max_sto_no;
            $filterNum++;
            $filterNumLength = strlen($filterNum);
            $clearNumber = str_pad($filterNum, $filterNumLength + 5, '0', STR_PAD_LEFT);
            $sto_no = "STO" . $clearNumber;
        } else {
            $filterNum = 1;
            $sto_no = "STO00000" . $filterNum;
        }
        return $sto_no;
    }
}
