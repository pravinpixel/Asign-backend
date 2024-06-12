<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelTransferDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'label_transfer_id', 'product_id', 'qty'
    ];

    public $timestamps = false;

    public const STATUS = [
        'ordered' => [
            'id' => 'requested', 'label' => 'Ordered',
            'color' => 'statusYellow'
        ],
        'packed' => [
            'id' => 'issued', 'label' => 'Packed',
            'color' => 'statusOrange'
        ],
        'transit' => [
            'id' => 'issued', 'label' => 'Transit',
            'color' => 'statusLavender'
        ],
        'fulfilled' => [
            'id' => 'closed', 'label' => 'Fulfilled',
            'color' => 'statusGreen'
        ],
    ];


    public function transfer()
    {
        return $this->belongsTo(LabelTransfer::class, 'label_transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select('id', 'name');
    }

}
