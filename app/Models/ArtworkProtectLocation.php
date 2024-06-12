<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ArtworkProtectLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id', 'location_as', 'sub_location', 'address_line1', 'address_line2', 'city', 'state_id', 'country_id', 'pin_code', 'created_by'
    ];

    const UPDATED_AT = null;

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id')->select('id', 'name');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id')->select('id', 'name');
    }

}
