<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelDamageDetails extends Model
{
    use HasFactory;

    protected $table = 'label_damage_product_details';
    
    public $timestamps = false;
}
