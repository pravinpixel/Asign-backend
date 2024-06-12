<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediumType extends Model
{
    protected $table = 'medium_types';

    public function medium() {
        return $this->belongsTo(Medium::class, 'medium_id', 'id');
    }

}
