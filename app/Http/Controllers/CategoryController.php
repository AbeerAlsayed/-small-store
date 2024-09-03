<?php

namespace App\Http\Controllers;

use App\Http\Requests\category\RequestUpdateCategory;
use App\Http\Requests\category\StoreRequest;
use App\Http\Requests\RequestCategory;
use App\Models\Category;
use App\Models\Product;
use App\services\CreateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


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

    public function show($id){
        $category = Category::with(['children', 'products','images'])->find($id);
        return response()->json(['category'=>$category,'success' => true, 'message' => "Category show Successfully",]);
    }

    public function store(StoreRequest $request){
        $category=$this->create_category->createCategory($request);
        return response()->json(['success' => true, 'Category' => $category], 200);
    }

    public function update(Request $request,$id){
        dd($request);
        $category=$this->create_category->updateCategory($request,$id);
        return response()->json(['success' => true, 'Category' => $category], 200);
    }

    public function destroy($id) {
        Product::where('category_id', $id)->delete();
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true, 'message' => "Category Deleted Successfully"]);
    }
}
