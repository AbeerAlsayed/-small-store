<?php
namespace App\services;


use App\Models\Category;
use App\Models\Image;
use App\Traits\UploadedFile;
use Illuminate\Http\Request;

class CreateCategory{
    use UploadedFile;
    public function createCategory(Request $request){
        $category=Category::create($request->all());
        $this->uploadFile($request,'url',$category,Category::class);
        return $category;
    }
    public function updateCategory(Request $request ,$id){
        $category= Category::findOrFail($id);
        $category->update($request->all());
        $this->updateFile($request,'url',$category,Category::class);
        return $category;
    }
}
