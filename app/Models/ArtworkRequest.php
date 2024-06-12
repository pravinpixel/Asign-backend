<?php

namespace App\Models;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ArtworkRequest extends Model
{
    use HasFactory;
    protected $table='artwork_requests';

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}