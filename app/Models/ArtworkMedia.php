<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtworkMedia extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'artwork_id',
        'tag',
        'value',
        'detail'
    ];

    public $timestamps = false;

    protected $casts = [
        'detail' => 'array',
    ];

    protected function Value(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value == NUll) ? NUll : config('app.image_url') . $value,
        );
    }
}
