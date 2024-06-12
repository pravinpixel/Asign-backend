<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerKycStatus extends Model
{
    use HasFactory;
    protected $table='customer_kyc_status';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'request' => 'array',
        'response' => 'array',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
