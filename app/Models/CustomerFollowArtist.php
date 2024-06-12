<?php

namespace App\Models;

use App\Helpers\UtilsHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerFollowArtist extends Model
{

    protected $fillable = [
        'customer_id', 'artist_id'
    ];

    public $timestamps = ["created_at"];

    public function getUpdatedAtColumn()
    {
        return null;
    }


    public function followCustomer()
    {
        $path = UtilsHelper::getStoragePath();
        $image = DB::Raw("IF(profile_image IS NULL or profile_image='', '', CONCAT('" . $path . "', profile_image)) AS profile_image");
        return $this->belongsTo(Customer::class, 'artist_id')->select('id', 'display_name', 'full_name', 'date_of_birth', 'date_of_death', $image);
    }
    public function artist()
    {
        $path = UtilsHelper::getStoragePath();
        $image = DB::Raw("IF(profile_image IS NULL or profile_image='', '', CONCAT('" . $path . "', profile_image)) AS profile_image");
        return $this->belongsTo(Customer::class, 'artist_id')->where('account_type', 'artist')->select('id', 'display_name', 'full_name', 'date_of_birth', 'date_of_death', $image);
    }

    public function business()
    {
        $path = UtilsHelper::getStoragePath();
        $image = DB::Raw("IF(profile_image IS NULL or profile_image='', '', CONCAT('" . $path . "', profile_image)) AS profile_image");
        return $this->belongsTo(Customer::class, 'artist_id')->where('account_type', 'business')->select('id', 'display_name', 'full_name', 'date_of_birth', 'date_of_death', 'city', 'state_id', 'country_id', $image);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
