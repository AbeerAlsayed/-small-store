<?php
namespace App\services;


use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Traits\UploadedFile;
use Illuminate\Http\Request;

class CreateProduct{
    use UploadedFile;
    public function createProduct(Request $request){
        $product=Product::create($request->all());
        $this->uploadFile($request,'url',$product);
        return $product;
    }
}
