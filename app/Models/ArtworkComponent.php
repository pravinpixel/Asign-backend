<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtworkComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artwork_id',
        'asign_no',
        'accession_no',
        'technique_used',
        'technique_others',
        'is_signature',
        'signature',
        'is_inscription',
        'recto_inscription',
        'verso_inscription',
        'base_inscription',
        'cover_image',
        'location_as',
        'sub_location',
        'address_line1',
        'address_line2',
        'city',
        'state_id',
        'country_id',
        'pin_code',
        'measurement_type_id',
        'shape_id',
        'dimension_size',
        'height',
        'width',
        'depth',
        'diameter',
        'height_cm',
        'width_cm',
        'depth_cm',
        'diameter_cm',
        'weight_size',
        'weight',
        'mediums',
        'surface',
        'surface_other',
        'medium_other',
        'verifier_id',
    ];

    protected $appends = ['medium_data', 'surface_data', 'technique_data'];


    protected function CoverImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value==null) ? null : config('app.image_url').$value,
        );
    }

    public function getMediumDataAttribute()
    {
        if (isset($this->mediums) && $this->mediums != NULL) {
            $mediums = Medium::whereIn('id', explode(',', $this->mediums))->get(['id', 'name']);
            if ($mediums) {
                return $mediums;
            }
            return [];
        }
        return [];
    }

    public function getSurfaceDataAttribute()
    {
        if (isset($this->surface) && $this->surface != NULL) {
            $surfaces = Surface::whereIn('id', explode(',', $this->surface))->get(['id', 'name']);
            if ($surfaces) {
                return $surfaces;
            }
            return [];
        }
        return [];
    }

    public function getTechniqueDataAttribute()
    {
        if (isset($this->technique_used) && $this->technique_used != NULL) {
            $techniques = Technique::whereIn('id', explode(',', $this->technique_used))->get(['id', 'name']);
            if ($techniques) {
                return $techniques;
            }
            return [];
        }
        return [];
    }

    public function shape()
    {
        return $this->belongsTo(Shape::class, 'shape_id', 'id')->select('id', 'name');
    }

    public function measurementType()
    {
        return $this->belongsTo(MeasurementType::class, 'measurement_type_id', 'id')->select('id', 'name');
    }

    public function country() {
        return $this->belongsTo(Country::class)->select('id', 'name');
    }

    public function state() {
        return $this->belongsTo(State::class)->select('id', 'name');
    }

}
