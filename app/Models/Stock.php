<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'location_id',
        'stock',
        'agent',
        'transit'
    ];

    public $timestamps = false;


    public function location()
    {
        return $this->belongsTo(BranchLocation::class, 'location_id')->withTrashed();
    }
}
