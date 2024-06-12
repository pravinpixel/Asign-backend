<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class GalleryPublication extends Model
{
    use HasFactory;
    protected function ImageOfBook(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value==NUll) ? NUll : config('app.image_url').$value,
        );
    }
}
