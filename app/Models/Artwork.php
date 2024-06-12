<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Artwork extends Model
{
    use HasFactory;

    protected $appends = ['artist_name', 'type_name'];


    public function getArtistNameAttribute()
    {
        if (isset($this->artist_id) && $this->artist_id != NULL) {
            $artist = Customer::find($this->artist_id);
            if ($artist) {
                return ($artist->full_name == NULL) ? '' : $artist->full_name;
            }
            return '';
        }
        return ($this->unknown_artist == NULL) ? '' : $this->unknown_artist;
    }

    protected function CreationYearFrom(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value == NULL) ? '' : $value,
        );
    }

    protected function Title(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value == NULL) ? '' : $value,
        );
    }

    public function getTypeNameAttribute()
    {
        if (isset($this->type_id) && $this->type_id != NULL) {
            $type = ObjectType::find($this->type_id);
            if ($type) {
                return $type->name;
            }
            return '';
        }
        return '';
    }

    protected function LocationDetails(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value),
        );
    }

    public function artist()
    {
        return $this->belongsTo(Customer::class, 'artist_id', 'id')->select('id', 'display_name');
    }

      public function images()
    {
        return $this->hasMany(ArtworkMedia::class, 'artwork_id', 'id')->where('tag', '!=', 'video')->orderBy('tag', 'asc');
    }
    public function videos()
    {
        return $this->hasMany(ArtworkMedia::class, 'artwork_id', 'id')->where('tag', 'video');
    }

    public function auctions()
    {
        return $this->hasMany(ArtworkAuction::class, 'artwork_id', 'id');
    }

    public function publications()
    {
        return $this->hasMany(ArtworkPublication::class, 'artwork_id', 'id')->orderBy('date', 'desc');
    }

    public function locations()
    {
        return $this->hasMany(ArtworkLocation::class, 'artwork_id', 'id');
    }

    public function lastLocations()
    {
        return $this->locations()->orderBy('id', 'desc')->limit(5);
    }

    public function latestLocation()
    {
        return $this->hasOne(ArtworkLocation::class, 'artwork_id', 'id')->orderBy('id', 'desc');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id')->select('id', 'name');
    }
    public function movement()
    {
        return $this->belongsTo(Movement::class, 'movement_id', 'id')->select('id', 'name');
    }

    public function notes($table = null)
    {

        if ($table == 'inventory') {
            return $this->hasMany(ArtworkInventoryNote::class, 'artwork_id', 'id');
        } elseif ($table == 'object') {
            return $this->hasMany(ArtworkObjectNote::class, 'artwork_id', 'id');
        } elseif ($table == 'record') {
            return $this->hasMany(ArtworkRecordNote::class, 'artwork_id', 'id');
        } elseif ($table == 'media') {
            return $this->hasMany(ArtworkMediaNote::class, 'artwork_id', 'id');
        }

        return $this->hasMany(ArtworkInventoryNote::class, 'artwork_id', 'id');

    }

    public function inventoryNotes($tag = null)
    {
        if ($tag) {
            return $this->hasMany(ArtworkInventoryNote::class, 'artwork_id', 'id')->where('tag', $tag);
        } else {
            return $this->hasMany(ArtworkInventoryNote::class, 'artwork_id', 'id');
        }
    }

    public function objectNotes($tag = null)
    {
        if ($tag) {
            return $this->hasMany(ArtworkObjectNote::class, 'artwork_id', 'id')->where('tag', $tag);
        } else {
            return $this->hasMany(ArtworkObjectNote::class, 'artwork_id', 'id');
        }
    }

    public function recordNotes($tag = null)
    {
        if ($tag) {
            return $this->hasMany(ArtworkRecordNote::class, 'artwork_id', 'id')->where('tag', $tag);
        } else {
            return $this->hasMany(ArtworkRecordNote::class, 'artwork_id', 'id');
        }
    }

    public function mediaNotes($tag = null)
    {
        if ($tag) {
            return $this->hasMany(ArtworkMediaNote::class, 'artwork_id', 'id')->where('tag', $tag);
        } else {
            return $this->hasMany(ArtworkMediaNote::class, 'artwork_id', 'id');
        }
    }

    public function type()
    {
        return $this->belongsTo(ObjectType::class, 'type_id', 'id')->select('id', 'name');
    }

    public function shape()
    {
        return $this->belongsTo(Shape::class, 'shape_id', 'id')->select('id', 'name');
    }

    public function measurementType()
    {
        return $this->belongsTo(MeasurementType::class, 'measurement_type_id', 'id')->select('id', 'name');
    }

    public function components()
    {
        return $this->hasMany(ArtworkComponent::class, 'artwork_id', 'id');
    }

    public function provenances()
    {
        return $this->hasMany(ArtworkProvenance::class, 'artwork_id', 'id');
    }
}
