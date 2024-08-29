<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Models\Image;
use App\Traits\UploadedFile;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    use UploadedFile;
    public function index(){
        $img=Image::all();
        return response()->json($img,'200');
    }
    public function store(Request $request){
        $url=$this->uploadFile($request);
       $img= Image::create([
            'url'=>$url,
            'alter_img'=>$request->alter_img,
            'product_id'=>$request->product_id,
        ]);
        return response()->json($img,'200');

    }
}
