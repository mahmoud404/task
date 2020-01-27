<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    public function imageable()
    {
        return $this->morphTo();
    }

    public function getFullUrlAttribute()
    {
        return Storage::disk('s3')->url($this->url);
    }
}
