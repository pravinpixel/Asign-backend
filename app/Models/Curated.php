<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curated extends Model
{
    use HasFactory;

    protected function Image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value == null || $value == '') ? '' : config('app.image_url') . $value,
        );
    }


}
