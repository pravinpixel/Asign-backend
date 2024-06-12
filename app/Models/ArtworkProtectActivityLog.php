<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ArtworkProtectActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id',
        'tag',
        'user_agent',
        'message',
        'ip_address'
    ];

    public const COLOURS = [
        'admin' => 'statusYellow',
        'authenticator' => 'statusPink',
        'customer' => 'statusOrange',
        'conservator' => 'statusLavender',
        'field_agent' => 'statusSkyblue',
        'supervisor' => 'statusSkyblue',
        'default' => 'statusOrange'
    ];

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }




}
