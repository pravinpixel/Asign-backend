<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'token', 'is_verified'
    ];
}
