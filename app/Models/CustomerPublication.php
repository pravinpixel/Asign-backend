<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPublication extends Model
{
    use SoftDeletes;

    protected $fillable = [
       'customer_id',  'title', 'author', 'date', 'image'
    ];
    protected function Image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value==NUll) ? NUll : config('app.image_url').$value,
        );
    }

}
