<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdParty extends Model
{
    use HasFactory;

    protected $table = 'thirdparty_variables';


    protected $casts = [
        'variables' => 'array'
    ];
}
