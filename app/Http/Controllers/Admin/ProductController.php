<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(){
        try {
            $products = Product::paginate(3);
            return response()->json([
                "success" => true,
                "data" => $products,
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong",
            ],500);
        }
    }
    public function store(ProductStoreRequest $request){
        // return response()->json([
        //     "message" => $request->all()
        // ]);
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
            $product = Product::create([
                "name"=> $request->name,
                "image" => $imageName,
                "description" => $request->description,
                "category_id" => $request->category_id,
            ]);
        
            $request->image->storeAs('products', $imageName,'public');
            return response()->json([
                "message" => "Product Created Successfully",
            ],201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message" => "Something Went Wrong",
            ],500);
        }
    }
    public function edit($id){
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                "success"=> true,
                "data" => $product,
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Product Not Found"
            ],500);
        }
    }
    public function update(Request $request,$id){
        $validate = $request->validate([
            "name" => "required|string|max:255",
            "image" => "nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048",
            "description" => "required",
            "category_id" => "required|exists:categories,id",
        ]);
        try {
            $product = Product::findOrFail($id);
            if( $request->hasFile("image") ){
                if($product->image){
                    Storage::disk('public')->delete('products/'.$product->image);
                }
                $imageName = Str::random(32).".".$request->image->getClientOriginalName();
                $request->image->storeAs("products", $imageName,"public");
                $product->image = $imageName;
            }
            $product->name = $validate['name'];
            $product->description = $validate['description'];
            $product->category_id = $validate['category_id'];
            $product->save();
            return response()->json([
                'success'=> true,
                'data' => $product,
                'message' => 'Product Updated Successfully',
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ],500);
        }
    }
    public function destroy($id){
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json([
                'success'=> true,
                'message' => 'Product Deleted Successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message' => 'Something Went Wrong'
            ],500);
        }
    }
}
