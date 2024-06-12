<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'label_id', 'product_id', 'qty', 'issued_qty', 'consumed_qty', 'returned_qty', 'adjust_qty', 'damaged_qty', 'status'
    ];

    public $timestamps = false;

    public const STATUS = [
        'requested' => [
            'id' => 'requested', 'label' => 'Requested',
            'color' =>  'statusYellow'
        ],
        'issued' => [
            'id' => 'issued', 'label' => 'Issued',
            'color' => 'statusOrange'
        ],
        'closed' => [
            'id' => 'closed', 'label' => 'Closed',
            'color' =>  'statusGreen'
        ],
    ];


    public function label()
    {
        return $this->belongsTo(Label::class, 'label_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select('id', 'name');
    }

}
