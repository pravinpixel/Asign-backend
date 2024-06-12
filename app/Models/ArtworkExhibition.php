<?php

namespace App\Models;

use App\Helpers\UtilsHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ArtworkExhibition extends Model
{
    use  SoftDeletes;

    protected $fillable = [
        'artwork_id',  'exhibition_id'
    ];

    public $timestamps = false;

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

    public function exhibition()
    {
        $path = UtilsHelper::getStoragePath();
        $image = DB::Raw("IF(cover_image IS NULL or cover_image='', '', CONCAT('" . $path . "', cover_image)) AS image");
        return $this->belongsTo(Exhibition::class)->select('id', 'name', 'type', 'hosted_by', 'collaborators', 'city', 'country_id', 'state_id', 'description', 'venue', 'from_date', 'to_date', $image);
    }
}
