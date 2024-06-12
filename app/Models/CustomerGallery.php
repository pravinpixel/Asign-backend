<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGallery extends Model
{
    public $timestamps = false;


    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

}
