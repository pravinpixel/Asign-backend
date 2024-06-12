<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtworkPrivateViewRequest extends Model
{
    use HasFactory;

    public function artwork()
    {
        return $this->belongsTo('App\Models\Artwork', 'artwork_id', 'id');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }
}
