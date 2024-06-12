<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id', 'request_date', 'agent_id', 'location_id', 'status', 'issued_at', 'issued_by', 'return_at', 'return_by', 'created_by'
    ];

   const UPDATED_AT = null;


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
            'color' => 'statusGreen'
        ],
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')->select('id', 'name');
    }

    public function location()
    {
        return $this->belongsTo(BranchLocation::class, 'location_id')->select('id', 'location as name')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select('id', 'name');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by')->select('id', 'name');
    }

    public function returnBy()
    {
        return $this->belongsTo(User::class, 'return_by')->select('id', 'name');
    }

    public function products()
    {
        return $this->hasMany(LabelProduct::class, 'label_id');
    }

    public function productDetails()
    {
        return $this->hasMany(LabelProductDetail::class, 'label_id');
    }

    public function balanceProductDetails()
    {
        return $this->productDetails()->where('status', LabelProductDetail::STATUS['issued']['id']);
    }
    public function returnedProductDetails()
    {
        return $this->productDetails()->where('status', LabelProductDetail::STATUS['returned']['id']);
    }



}
