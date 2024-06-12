<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Notification extends Model
{
    use HasFactory;
    protected $fillable = [];
    protected function CreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value, 'UTC')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A'),
        );
    }
}
