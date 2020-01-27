<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable =['name','email','phone'];

    public function image()
    {
        return $this->morphMany('App\Image', 'imageable');
    }


    public function getCvFileAttribute()
    {
        return $this->image()->where('type','cv')->first()->full_url ?? 'default-image.png';
    }

    public function getPassportImageAttribute()
    {
        return $this->image()->where('type','passport_image')->first()->full_url ?? 'default-image.png';
    }

}
