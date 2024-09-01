<?php

namespace App\Traits;

//use http\Env\Request;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait UploadedFile
{
    public function uploadFile(Request $request,$attr,$model,$imageableType,$file_images='images', $disk = 'public',){
        if ($request->hasFile($attr)) {
            $files = $request->file($attr);
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs($file_images, $filename, $disk);
                Image::create([
                    'imageable_id'=>$model->id,
                    'imageable_type'=>$imageableType,
                    'url'=>$path,
                ]);
            }
        }
    }

    public function updateFile(Request $request, $attr, $model,$imageableType, $file_images = 'images', $disk = 'public')
    {
        if ($request->hasFile($attr)) {
            // Retrieve the old image URLs from the database
            $oldImages = Image::where('imageable_id', $model->id)
                ->where('imageable_type', $imageableType)
                ->get();

            // Delete old images from storage
            foreach ($oldImages as $oldImage) {
                Storage::disk($disk)->delete($oldImage->url);
                $oldImage->delete();
            }

            // Store new images and update the database
            $files = $request->file($attr);
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs($file_images, $filename, $disk);
                Image::create([
                    'imageable_id'=>$model->id,
                    'imageable_type'=>$imageableType,
                    'url'=>$path,
                ]);
            }
        }
    }

}
