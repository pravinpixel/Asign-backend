<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectType extends Model
{
    use HasFactory,SoftDeletes;

    public function gst()
    {
        return $this->belongsTo(GstDetails::class, 'gst_id', 'id');
    }
    
}
