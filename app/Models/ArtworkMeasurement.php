<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtworkMeasurement extends Model
{
    use HasFactory, SoftDeletes;


    public $timestamps = false;
    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }


}
