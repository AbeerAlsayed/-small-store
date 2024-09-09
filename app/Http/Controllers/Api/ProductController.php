<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProductRequest;
use App\Models\Product;
use App\services\CreateProduct;


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

    public function show($id){
        $product = Product::with('images')->find($id);
        return response()->json(['success' => true, 'Product' => $product]);
    }

    public function store(SaveProductRequest $request){
        $product=$this->create_product->createProduct($request);
        return response()->json(['success' => true, 'Product' => $product], 200);
    }

    public function update(SaveProductRequest $request, $id){
        $product=$this->create_product->updateProduct($request,$id);
        return response()->json(['success' => true, 'Product' => $product], 200);
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => true, 'message' => "Product Delete Successfully",]);
    }
}
