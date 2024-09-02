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
    public function updateCategory(Request $request ,$id){
        $category= Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors(),], 422);
        }
        $category->update($validator->validated());
        $this->updateFile($request,'url',$category,Category::class);
        return $category;
    }
}
