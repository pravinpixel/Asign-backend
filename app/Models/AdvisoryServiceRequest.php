<?php

namespace App\Models;
use App\Models\Customer;
use App\Models\Advisoryservicelist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisoryServiceRequest extends Model
{
    use HasFactory;
    protected $table='advisory_service_requests';

public function customer()
{
    return $this->hasOne(Customer::class, 'id', 'customer_id');
}

public function advisoryservice()
{
    return $this->hasOne(Advisoryservicelist::class, 'id', 'advisory_service_id');
}

}
