<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubResource;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories=Category::with(['children', 'products','images'])->get();
            if ($categories) {
                return response()->json(['success' => true, 'Category' => $categories,],200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(),],500);
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'parent_id' => 'nullable|exists:categories,id'
            ]);
            $category=Category::create($request->all());
            if ($request->hasFile('url')) {
                $files = $request->file('url');
                foreach ($files as $file) {
                    $filename = $file->getClientOriginalName();
                    $path = $file->storeAs('images', $filename, 'public');
                    Image::create([
                        'imageable_id' => $category->id,
                        'imageable_type' => Category::class,
                        'url' => $path,
                    ]);
                }
            }
            if ($category) {
                return response()->json(['Category'=>$category,$category->images],  200,);
            } else {
                return response()->json(['success' => false, 'message' => "Some Problem"]);
            }

        } catch (Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage(),]);
        }
    }

    public function show_sub(){
        $categories = Category::with(['children', 'products','images'])->get();
        return response()->json(['categories' => SubResource::collection($categories)]);
    }
    public function show($id){
        $category = Category::with(['children', 'products','images'])->find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        return response()->json($category);
    }

    public function update(Request $request,$id)
    {
        try {
            $categories = Category::findOrFail($id);
            $categories->update($request->all());
            return response()->json(['category'=>$categories,'success' => true, 'message' => "Category Update Successfully",]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(),]);
        }
    }

    public function destroy($id)
    {
        $categories = Category::findOrFail($id);

        $result = $categories->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => "Category Delete Successfully",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Some Problem",
            ]);
        }
    }
}
