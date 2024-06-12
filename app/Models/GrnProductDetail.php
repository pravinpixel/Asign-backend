<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_id',
        'location_id',
        'order_id',
        'is_grn_saved',
        'is_sto_grn_saved',
        'product_id',
        'op_product_id',
        'scanned_product_id',
        'category',
        'quantity',
        'type',
        'status'
    ];

    public $timestamps = false;

    public const STATUS = [
        'open' => [
            'id' => 'open', 'label' => 'Open',
            'color' => 'statusGreen'
        ],
        'issued' => [
            'id' => 'issued', 'label' => 'Issued',
            'color' => 'statusYellow',
        ],
        'transit' => [
            'id' => 'transit', 'label' => 'Transit',
            'color' =>  'statusOrange'
        ],
        'packed' => [
            'id' => 'packed', 'label' => 'Packed',
            'color' => 'statusGreen'
        ],
        'transfered' => [
            'id' => 'transfered', 'label' => 'Transfered',
            'color' => 'statusGreen'
        ],
        'damaged' => [
            'id' => 'damaged', 'label' => 'Damaged',
            'color' => 'statusRed'
        ],
        'agent-damaged' => [
            'id' => 'agent-damaged',
            'label' => 'Damaged Agent',
            'color' => 'statusRed'
        ],
        'adjust' => [
            'id' => 'adjust', 'label' => 'Adjust',
            'color' => 'statusSkyblue'
        ],
    ];

    public function location()
    {
        return $this->belongsTo(BranchLocation::class, 'location_id')->select('id', 'location as name')->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select('id', 'name');
    }

}
