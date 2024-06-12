<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtworkPublication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artwork_id',
        'name',
        'author',
        'hosted_by',
        'page_no',
        'date',
        'image',
        'published_by',
    ];

    protected function Image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value == null) ? null : config('app.image_url') . $value,
        );
    }


}
