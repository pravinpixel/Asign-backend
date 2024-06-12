<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockByAgent extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'agent_id',
        'product_id',
        'location_id',
        'in_hand',
        'consumed',
        'returned',
        'adjust',
        'damaged',
        'balance'
    ];
    public function location()
    {
        return $this->belongsTo(BranchLocation::class, 'location_id')->withTrashed();
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')->select('id', 'name');
    }


}
