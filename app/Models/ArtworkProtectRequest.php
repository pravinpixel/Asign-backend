<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ArtworkProtectRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'verify_status' => 'array',
        'status_timeline' => 'array',
        'inventory_label' => 'array',
        'auth_label' => 'array',
        'child_labels' => 'array'
    ];


    public const STATUS = [
        'authentication' => [
            'id' => 'authentication', 'label' => 'Authentication',
            'color' => 'statusPink', 'role' => 'authenticator',
            'next' => 'inspection'
        ],
        'inspection' => [
            'id' => 'inspection', 'label' => 'Inspection',
            'color' => 'statusLavender', 'role' => 'conservator',
            'next' => 'asign-protect'
        ],
        'asign-protect' => [
            'id' => 'asign-protect', 'label' => 'Asign Protect+',
            'color' => 'statusSkyblue', 'role' => 'field_agent',
            'next' => 'approved'
        ],
        'approved' => [
            'id' => 'approved', 'label' => 'Approved',
            'color' => 'statusGreen', 'role' => '',
            'next' => ''
        ],
        'rejected' => [
            'id' => 'rejected', 'label' => 'Rejected',
            'color' => 'statusOrange', 'role' => '',
            'next' => ''
        ],
        'authentication-review' => [
            'id' => 'authentication-review', 'label' => 'Review',
            'color' => 'statusYellow', 'role' => 'supervisor',
            'next' => 'inspection'
        ],
        'inspection-review' => [
            'id' => 'inspection-review', 'label' => 'Review',
            'color' => 'statusYellow', 'role' => 'supervisor',
            'next' => 'asign-protect'
        ],
        'asign-protect-review' => [
            'id' => 'asign-protect-review', 'label' => 'Review',
            'color' => 'statusYellow', 'role' => 'supervisor',
            'next' => 'approved'
        ],
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function reviewer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function artwork()
    {
        return $this->hasOne(Artwork::class, 'id', 'artwork_id');
    }

    public function secondaryLocations()
    {
        return $this->hasMany(ArtworkProtectLocation::class, 'request_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(ArtworkProtectActivityLog::class, 'request_id', 'id')->orderBy('id', 'desc');
    }
}
