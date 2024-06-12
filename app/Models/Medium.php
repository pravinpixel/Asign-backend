<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Medium extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="mediums";

    public function types() {
        return $this->hasMany(MediumType::class, 'medium_id', 'id');
    }

}
