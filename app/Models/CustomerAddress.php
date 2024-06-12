<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'location_as', 'sub_location', 'address_line1', 'address_line2', 'city', 'state_id', 'country_id', 'pin_code', 'is_default'
    ];

    public $timestamps = false;

    public function country() {
        return $this->belongsTo(Country::class, 'country_id')->select('id', 'name');
    }
    public function state() {
        return $this->belongsTo(State::class, 'state_id')->select('id', 'name');
    }

}