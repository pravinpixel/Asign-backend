<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtworkObjectNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'artwork_id',
        'created_by',
        'tag',
        'notes',
    ];

    public $timestamps = ["created_at"];

    public function getUpdatedAtColumn() {
        return null;
    }
    public function artwork()
    {
        return $this->belongsTo(Artwork::class, 'artwork_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
