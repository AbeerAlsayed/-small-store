<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\category\RequestUpdateCategory;
use App\Http\Requests\category\StoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\services\CreateCategory;
use Illuminate\Http\Request;


class CategoryController extends BaseController
{
    public CreateCategory $create_category;
    public function __construct(CreateCategory $create_category){
        $this->create_category = $create_category;
    }
    public function index(){
        $categories = Category::whereNull('parent_id')->with(['children', 'products', 'images'])->get();
        return $this->sendResponse(['success' => true, 'Category' => $categories], 200);
    }

    public function show($id){
        $category = Category::with(['children', 'products','images'])->find($id);
        return $this->sendResponse(['category'=>$category,'success' => true, 'message' => "Category show Successfully",],200);
    }

    public function store(StoreRequest $request){
        $category=$this->create_category->createCategory($request);
        return $this->sendResponse(['success' => true, 'Category' => $category], 200);
    }

    public function update(RequestUpdateCategory $request,$id){
        $category=$this->create_category->updateCategory($request,$id);
        return $this->sendResponse(['success' => true, 'Category' => $category], 200);
    }

    public function destroy($id) {
        Product::where('category_id', $id)->delete();
        $category = Category::findOrFail($id);
        $category->delete();
        return $this->sendResponse(['success' => true, 'message' => "Category Deleted Successfully"],200);
    }
}
