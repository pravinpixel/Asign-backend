<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Helpers\MasterHelper;
class Artist extends Model
{
    use HasFactory;
    
    protected function CoverImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value==NUll) ? NUll : config('app.image_url').$value,
        );
    }
    protected function ProfileImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value==NUll) ? NUll : config('app.image_url').$value,
        );
    }
    
}
