<?php


namespace App\Helpers;

use App\Image;
use Illuminate\Support\Facades\Storage;

trait UploadImage
{
    public function upload($file , $imageable , $type = null,$update=false)
    {
        $destination = public_path('uploads');
        $photo = strtolower(rand(000,999).str_replace(' ','-',$file->getClientOriginalName()));

        $s3 = Storage::disk('s3');

        $s3->put($photo, file_get_contents($file));

        if ($update)
        {
            $img = $imageable->image()->where('type',$type)->first();
            if ($img)
            {
                if(Storage::disk('s3')->exists($img->url)) {
                    Storage::disk('s3')->delete($img->url);
                }
            }else
            {
                $img = new Image();
            }

        }else
        {
            $img = new Image();
        }
        $img->url = $photo;
        $img->type = $type;
        $img->imageable()->associate($imageable)->save();
    }

    public function s3DeleteImage($url)
    {
        if(Storage::disk('s3')->exists($url)) {
            Storage::disk('s3')->delete($url);
        }
    }

}
