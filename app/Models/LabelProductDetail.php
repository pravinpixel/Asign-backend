<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'label_id', 'product_id', 'code', 'envelope', 'category', 'qty', 'issued', 'consumed', 'returned', 'adjust', 'damaged', 'status'
    ];

    public $timestamps = false;

    public const STATUS = [
        'issued' => [
            'id' => 'issued', 'label' => 'Issued',
            'color' => 'statusYellow'
        ],
        'consumed' => [
            'id' => 'consumed', 'label' => 'Consumed',
            'color' =>  'statusGreen'
        ],
        'damaged' => [
            'id' => 'damaged', 'label' => 'Damaged',
            'color' => 'statusRed'
        ],
        'returned' => [
            'id' => 'returned', 'label' => 'Returned',
            'color' => 'statusOrange'
        ],
        'adjust' => [
            'id' => 'adjust', 'label' => 'Adjust',
            'color' => 'statusSkyblue'
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
