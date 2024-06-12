<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtworkAuction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'artwork_id',
        'house',
        'name',
        'start_date',
        'end_date',
        'auction_no',
        'lot_no',
        'location',
    ];
    public $timestamps = false;
}
