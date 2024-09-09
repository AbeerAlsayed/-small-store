<?php
namespace App\services;


use App\Http\Requests\category\RequestUpdateCategory;
use App\Http\Requests\category\StoreRequest;
use App\Http\Requests\RequestCategory;
use App\Models\Category;
use App\Traits\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateCategory{
    use UploadedFile;
    public function createCategory(StoreRequest $request){
        $category=Category::create($request->all());
        $this->uploadFile($request,'url',$category,Category::class);
        return $category;
    }
    public function updateCategory(RequestUpdateCategory $request ,$id){
        $category= Category::findOrFail($id);
        $category->update($request->all());
        $this->updateFile($request,'url',$category,Category::class);
        return $category;
    }
}
