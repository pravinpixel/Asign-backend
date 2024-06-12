<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LabelDamage extends Model
{
    use HasFactory;

    public function getRequestId() {
        $request = DB::table('label_damages')->latest()->first();
        if($request){
            $numbersIn = substr($request->reference_id, -6);
            $numbersIn++;
            $numbersInLength = strlen($numbersIn);
            $final = str_pad($numbersIn, $numbersInLength + 5, '0', STR_PAD_LEFT);

            return "DIL" . $final;
        }

        return "DIL000001";
    }

    public function damageProducts()
    {
        return $this->hasMany(LabelDamageProducts::class,'damage_id','id');
    }
    
}
