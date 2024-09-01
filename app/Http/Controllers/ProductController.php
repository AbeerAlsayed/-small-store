<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\services\CreateCategory;
use App\services\CreateProduct;
use App\Traits\UploadedFile;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public CreateProduct $create_product;
    public function __construct(CreateProduct $create_product){
        $this->create_product = $create_product;
    }
    public function index(){
        $products=Product::with('images')->get();
        return response()->json(['success' => true, 'Product' => $products,],200);
    }

    public function store(Request $request){
        $product=$this->create_product->createProduct($request);
        return response()->json(['success' => true, 'Product' => $product], 200);
    }

    public function show($id){
        $product = Product::with('images')->find($id);
        return response()->json(['success' => true, 'Product' => $product]);
    }

    public function update(Request $request,$id){
        $product=$this->create_product->createProduct($request);
        return response()->json(['success' => true, 'Product' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => true, 'message' => "Product Delete Successfully",]);

    }


}
