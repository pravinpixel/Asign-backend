<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id', 'type', 'date', 'location_id', 'agent_id', 'status', 'created_by'
    ];

    const UPDATED_AT = null;

    public const STATUS = [
        'enquiry' => [
            'id' => 'enquiry', 'label' => 'Enquiry',
            'color' => 'statusOrange'
        ],
        'enquiry-adjust' => [
            'id' => 'enquiry-adjust', 'label' => 'Enquiry',
            'color' => 'statusOrange'
        ],
        'override' => [
            'id' => 'override', 'label' => 'Enquiry',
            'color' => 'statusOrange'
        ],
        'enquiry-start' => [
            'id' => 'enquiry-start', 'label' => 'Enquiry',
            'color' => 'statusOrange'
        ],
        'enquiry-stop' => [
            'id' => 'enquiry-stop', 'label' => 'Enquiry',
            'color' => 'statusOrange'
        ],
        'complete' => [
            'id' => 'complete', 'label' => 'Complete',
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

    public function products()
    {
        return $this->hasMany(StockCheckProduct::class, 'stock_check_id');
    }

    public function productDetails()
    {
        return $this->hasMany(StockCheckProductDetail::class, 'stock_check_id');
    }

}
