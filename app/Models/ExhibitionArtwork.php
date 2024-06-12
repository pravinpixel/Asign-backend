<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionArtwork extends Model
{
    public $timestamps = false;

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

}
