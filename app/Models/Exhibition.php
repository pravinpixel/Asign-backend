<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Exhibition extends Model
{
    use HasFactory;
   protected $appends=['city_name'];
   protected $casts = [
        'artist_ids' => 'array',
    ];
    protected function CoverImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value==NUll) ? NUll : config('app.image_url').$value,
        );
    }
     public function getCityNameAttribute()
    {
        $city=City::where("id", $this->city_id)->first();
        if($city){
            return $city->name;
        }

        return "";
    }

    public function artworks()
    {
        return $this->hasMany(ExhibitionArtwork::class);
    }
}
