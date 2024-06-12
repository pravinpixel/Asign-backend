<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMediaMention extends Model
{
    use SoftDeletes;

    protected $fillable = [
       'customer_id',  'title', 'published_by', 'date'
    ];


}
