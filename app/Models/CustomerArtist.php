<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerArtist extends Model
{   
    protected $fillable = [
        'is_verified',
        'representation_reject_id',
        'representation_reject_reason',
    ];
    public $timestamps = false;
    public function artist()
    {
        return $this->hasOne(Customer::class,'id','customer_artist_id');
    }

}
