<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class PreloginPage extends Model
{
    use HasFactory;
    protected $table='pre_login_pages';
    protected $casts=['form_value'];
   // public function getDesktopsAttribute()
   //  {   
   //      $value=[];
   //      $desktops=explode(',', $this->desktop_images);
   //      if(isset($desktops)){
   //      foreach($desktops as $desktop){
   //           if($desktop !=""){
   //       $value[]=config('app.image_url').$desktop;
   //              }
   //      }
   //      }
   //      return $value;
   //  }
   //  public function getMobilesAttribute()
   //  {   
   //      $value=[];
   //      $mobiles=explode(',', $this->mobile_images);
   //      if(isset($mobiles)){
   //      foreach($mobiles as $mobile){
   //          if($mobile !=""){
   //      $value[]=config('app.image_url').$mobile;
   //          }
   //      }
   //      }
   //      return $value;
   //  }
     protected function FormValue(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
        );
    }
}
