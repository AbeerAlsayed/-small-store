<?php
namespace App\services;


use App\Http\Requests\SaveProductRequest;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Traits\UploadedFile;
use Illuminate\Http\Request;

class CreateProduct{
    use UploadedFile;
    public function createProduct(SaveProductRequest $request){
        $product=Product::create($request->all());
        $this->uploadFile($request,'url',$product,Product::class);
        return $product;
    }
    public function updateProduct(SaveProductRequest $request ,$id){
        $product= Product::findOrFail($id);
        $product->update($request->all());
        $this->updateFile($request,'url',$product,Product::class);
        return $product;
    }
}
