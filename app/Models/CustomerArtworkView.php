<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerArtworkView extends Model
{

    protected $fillable = [
        'customer_id', 'artwork_id', 'view_count', 'view_at'
    ];

    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function artwork()
    {
        return $this->belongsTo(Artwork::class, 'artwork_id');
    }

}
