<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtworkMedium extends Model
{
    use HasFactory;

    protected $table = 'artwork_mediums';

    protected $fillable = [
        'artwork_id', 'medium_id', 'medium_type_id'
    ];

    public $timestamps = false;
    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

    public function medium()
    {
        return $this->belongsTo(Medium::class);
    }

    public function mediumType()
    {
        return $this->belongsTo(MediumType::class);
    }


}
