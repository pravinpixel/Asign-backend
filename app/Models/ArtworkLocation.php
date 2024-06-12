<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ArtworkLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork_id',
        'location_id',
        'name',
        'status',
        'created_by',
        'updated_by',
    ];

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select(['id', 'name']);
    }

    public function updatedUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->select(['id', 'name']);
    }


}
