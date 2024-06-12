<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ArtworkInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork_id',
        'tag',
        'label',
        'is_parent',
        'location',
        'status',
        'created_by',
    ];

    public $timestamps = ["created_at"];

    public function getUpdatedAtColumn() {
        return null;
    }




    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select(['id', 'name']);
    }

}
