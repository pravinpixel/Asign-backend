<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_no', 'date', 'source_id', 'destination_id', 'reason_id', 'reason_others',
        'shipping_date', 'tracking_id', 'created_by', 'updated_by'
    ];

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

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')->select('id', 'name');
    }

    public function source()
    {
        return $this->belongsTo(BranchLocation::class, 'source_id')->select('id', 'location as name')->withTrashed();
    }

    public function destination()
    {
        return $this->belongsTo(BranchLocation::class, 'destination_id')->select('id', 'location as name')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select('id', 'name');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->select('id', 'name');
    }

    public function details()
    {
        return $this->hasMany(LabelTransferDetail::class);
    }


}
