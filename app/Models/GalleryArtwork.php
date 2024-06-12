<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryArtwork extends Model
{
    public $timestamps = false;

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

}
