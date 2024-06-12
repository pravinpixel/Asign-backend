<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Helpers\UtilsHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, Encryptable, SoftDeletes;

    protected $encryptable = [
        'gst_no'
    ];
    protected $appends = ['country_name', 'verify_value', 'verify_status'];

    public function getCountryNameAttribute()
    {
        $country = Country::where("id", $this->country_id)->first();
        if ($country) {
            return $country->name;
        }

        return "";
    }

    public function getVerifyValueAttribute()
    {
        $data = [];
        if ($this->is_mobile_verified == NULL) {
            $data[] = 'Mobile';
        }
        if ($this->is_email_verified == NULL) {
            $data[] = 'Email';
        }
        if ($this->account_type == 'artist') {
            if ($this->is_aadhaar_verify == 0) {
                $data[] = 'Aadhaar';
            }
            if ($this->is_pan_verify == 0) {
                $data[] = 'PAN';
            }
        }
        if ($this->account_type == 'business') {
            if ($this->gst_no == NULL) {
                $data[] = 'GST';
            }
            if ($this->is_pan_verify == 0) {
                $data[] = 'PAN';
            }

            if ($this->cin_no == NULL) {
                $data[] = 'CIN';
            }
        }
        if ($this->account_type == 'collector') {
            if ($this->register_as == "individual") {
                if ($this->is_aadhaar_verify == 0) {
                    $data[] = 'Aadhaar';
                }
                if ($this->is_pan_verify == 0) {
                    $data[] = 'PAN';
                }
            }

            if ($this->register_as == "company") {
                if ($this->company_type == "Private Limited") {
                    if ($this->is_pan_verify == 0) {
                        $data[] = 'PAN';
                    }
                    if ($this->gst_no == NULL) {
                        $data[] = 'GST';
                    }
                    if ($this->cin_no == NULL) {
                        $data[] = 'CIN';
                    }
                } else {
                    if ($this->is_pan_verify == 0) {
                        $data[] = 'PAN';
                    }
                    if ($this->gst_no == NULL) {
                        $data[] = 'GST';
                    }
                }
            }
        }

        if ($this->is_accept_terms == 0) {
            $data[] = 'Contract (A)';
        }

        if ($this->account_type !== 'collector' && $this->is_represent_contract != 1) {
            $data[] = 'Contract (R)';
        }

        if (count($data) == 0) {
            $data[] = 'All datas are verified';
        }
        return $data;
    }

    public function getVerifyStatusAttribute()
    {
        $data = [];
        if ($this->is_mobile_verified == NULL) {
            $data[] = 'Mobile';
        }
        if ($this->is_email_verified == NULL) {
            $data[] = 'Email';
        }
        if ($this->account_type == 'artist') {
            if ($this->is_aadhaar_verify == 0) {
                $data[] = 'Aadhaar';
            }
            if ($this->is_pan_verify == 0) {
                $data[] = 'PAN';
            }
        }
        if ($this->account_type == 'business') {
            if ($this->gst_no == NULL) {
                $data[] = 'GST';
            }
            if ($this->is_pan_verify == 0) {
                $data[] = 'PAN';
            }

            if ($this->cin_no == NULL) {
                $data[] = 'CIN';
            }
        }
        if ($this->account_type == 'collector') {
            if ($this->is_aadhaar_verify == 0) {
                $data[] = 'Aadhaar';
            }
            if ($this->is_pan_verify == 0) {
                $data[] = 'PAN';
            }
        }
        if (count($data) == 0) {
            return 'verified';
        }
        return 'unverified';
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {

            $unverified = false;
            if ($model->is_mobile_verified == null || $model->is_email_verified == null || $model->is_pan_verify == 0) {
                $unverified = true;
            }
            if ($model->account_type == 'artist') {
                if ($model->is_aadhaar_verify == 0) {
                    $unverified = true;
                }
            } elseif ($model->account_type == 'collector') {
                if ($model->register_as == 'individual') {
                    if ($model->is_aadhaar_verify == 0) {
                        $unverified = true;
                    }
                } elseif ($model->register_as == 'company') {
                    if ($model->gst_no == null) {
                        $unverified = true;
                    }
                    if ($model->company_type == "Private Limited") {
                        if ($model->cin_no == null) {
                            $unverified = true;
                        }
                    }
                }
            } else {
                if ($model->gst_no == null || $model->cin_no == null) {
                    $unverified = true;
                }
            }
            if ($model->account_type !== 'collector' && $model->is_represent_contract != 1) {
                $unverified = true;
            }
            if ($unverified) {
                $model->status = 'unverified';
            }

        });

    }

    public function scopesearchStatus($query, $status_data)
    {
        //return  $query->whereIn('verify_status',$status_data);
    }

    public function exhibitions()
    {
        return $this->hasMany(CustomerExhibition::class, 'customer_id', 'id');
    }

    public function educations()
    {
        return $this->hasMany(CustomerEducation::class, 'customer_id', 'id');
    }

    public function galleries()
    {
        return $this->hasMany(CustomerGallery::class, 'customer_id', 'id');
    }

    public function artists()
    {
        return $this->hasMany(CustomerArtist::class, 'customer_id', 'id')->orderBy('id', 'desc');
    }

    public function businesses()
    {
        return $this->hasMany(CustomerBusiness::class, 'customer_id', 'id')->orderBy('id', 'desc');
    }

    public function verifystatus()
    {
        return $this->hasOne(CustomerVerifyStatus::class, 'customer_id', 'id')->where('tag', 'schedule');
    }

    protected function Email(): Attribute
    {
        return Attribute::make(
            get: fn($value) => UtilsHelper::decrypt($value),
        );
    }

    protected function Mobile(): Attribute
    {
        return Attribute::make(
            get: fn($value) => UtilsHelper::decrypt($value),
        );
    }

    protected function City(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value == null) ? '' : $value,
        );
    }

    public function awards()
    {
        return $this->hasMany(CustomerAward::class, 'customer_id')->select('id', 'customer_id', 'name', 'date', 'awarded_by')->orderBy('date', 'desc');
    }

    public function mediaMentions()
    {
        return $this->hasMany(CustomerMediaMention::class, 'customer_id')->select('id', 'customer_id', 'title', 'date', 'published_by')->orderBy('date', 'desc');
    }

    public function collections()
    {
        return $this->hasMany(CustomerCollection::class, 'customer_id')->select('id', 'customer_id', 'collector', 'location')->orderBy('collector', 'asc');
    }

    public function publications()
    {

        return $this->hasMany(CustomerPublication::class, 'customer_id');
    }

    public function activity()
    {

        return $this->hasMany(CustomerActivityLog::class, 'customer_id')->orderByDesc('created_at');
    }

    protected function ProfileImage(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value == NUll) ? NUll : config('app.image_url') . $value,
        );
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class, 'created_by', 'id')->where('is_admin_created', 0)->whereJsonContains("menu_details", ['is_show' => true]);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id')->select('id', 'name');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id')->select('id', 'name');
    }
}
