<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Traits\UploadedFile;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use UploadedFile;
    public function index(){
        try {
            $products=Product::with('images')->get();
            if ($products) {
                return response()->json(['success' => true, 'Product' => $products,],200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(),],500);
        }
    }

    public function store(Request $request){
        try {
            $product=Product::create($request->all());
            if ($request->hasFile('url')) {
            $files = $request->file('url');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('images', $filename, 'public');
                Image::create([
                    'imageable_id'=>$product->id,
                    'imageable_type'=>Product::class,
                    'url'=>$path,
                ]);
            }

        }
          if ($product) {
              return response()->json(['product'=>$product,$product->images],200);
            } else {
                return response()->json(['success' => false, 'message' => "Some Problem"]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(),]);}
    }

    public function show($id)
    {
        try {
            $product = Product::with('images')->find($id);
            return response()->json(['success' => true, 'Product' => $product]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(),]);
        }
    }

    public function update(Request $request,$id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
            if ($request->hasFile('url')) {
                $files = $request->file('url');
                foreach ($files as $file) {
                    $filename = $file->getClientOriginalName();
                    $path = $file->storeAs('images', $filename, 'public');
                    Image::update([
                        'imageable_id'=>$product->id,
                        'imageable_type'=>Product::class,
                        'url'=>$path,
                    ]);
                }

            }
            if ($product) {
                return response()->json(['success' => true, 'message' => "Product Update Successfully",]);
            } else {
                return response()->json(['success' => false, 'message' => "Some Problem",]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(),]);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $result = $product->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => "Product Delete Successfully",]);
        } else {
            return response()->json(['success' => false, 'message' => "Some Problem",]);
        }
    }


}
