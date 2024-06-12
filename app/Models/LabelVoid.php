<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelVoid extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'envelope_code',
        'label_code',
        'location_id',
        'artwork_id',
        'request_id',
        'void_remarks',
        'void_reason_id'
    ];

    public function location()
    {
        return $this->belongsTo(BranchLocation::class, 'location_id')->select('id', 'location as name')->withTrashed();
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')->select('id', 'name');
    }
}
