<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMediumOther extends Model
{
    use HasFactory;


    protected $fillable = [
        'customer_id', 'medium_id', 'name'
    ];

    public $timestamps = false;

    public function medium()
    {
        return $this->belongsTo(Medium::class);
    }

}
