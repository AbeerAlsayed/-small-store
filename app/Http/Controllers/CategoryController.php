<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubResource;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\services\CreateCategory;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public CreateCategory $create_category;
    public function __construct(CreateCategory $create_category){
        $this->create_category = $create_category;
    }
    public function index(){
        $categories = Category::whereNull('parent_id')->with(['children', 'products', 'images'])->get();
        return response()->json(['success' => true, 'Category' => $categories], 200);
    }


    public function store(Request $request){
        $category=$this->create_category->createCategory($request);
        return response()->json(['success' => true, 'Category' => $category], 200);

    }

    public function show($id){
        $category = Category::with(['children', 'products','images'])->find($id);
        return response()->json(['category'=>$category,'success' => true, 'message' => "Category show Successfully",]);
    }

    public function update(Request $request,$id){
        $category=$this->create_category->updateCategory($request,$id);
        return response()->json(['success' => true, 'Category' => $category], 200);
    }

    public function destroy($id){
        $categories = Category::findOrFail($id);
        $categories->delete();
        return response()->json(['success' => true, 'message' => "Category Delete Successfully",]);
    }
}
