<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Http\Traits\ApiUseTrait;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Traits\FileUploadTrait;

class ProductController extends Controller
{
    use ApiUseTrait, FileUploadTrait;
    public function index(Request $request){
        try {
            $products = Product::paginate(($request->per_page) ? $request->per_page :5);
            $data['data'] = ProductResource::collection($products);
            $data['links'] = [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ];
            $data['meta'] = [
                'current_page' => $products->currentPage(),
                'from' => $products->firstItem(),
                'last_page' => $products->lastPage(),
                'path' =>  $products->url($products->currentPage()),
                'per_page' => $request->per_page,
                'to'=> $products->lastItem(),
                'total' => $products->total(),
            ];
            return $this->responseSuccess(true,"Products List", (object)$data,200);
        } catch (\Exception $e) {
            return $this->responseError(false,$e->getMessage(),500);
        }
    }
    public function store(ProductStoreRequest $request){
        try {
            //$imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
            $imageName = $this->uploadFile($request->image,'products');
            $product = Product::create([
                "name"=> $request->name,
                "image" => $imageName,
                "description" => $request->description,
                "category_id" => $request->category_id,
            ]);
            
            //$request->image->storeAs('products', $imageName,'public');
            return $this->responseSuccess(true,'Product Created Successfully',null,201);
        } catch (\Exception $e) {
            
            return $this->responseFail(false,$e->getMessage(),500);
        }
    }
    public function edit($id){
        try {
            $product = Product::findOrFail($id);
            return $this->responseSuccess(true,'A Product List', $product,200);
        } catch (\Exception $e) {
            return $this->responseError(false,$e->getMessage(),500);
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
                $imageName = $this->UpdateImage($request->image,'products',$product->image);
                $product->image = $imageName;
            }
            $product->name = $validate['name'];
            $product->description = $validate['description'];
            $product->category_id = $validate['category_id'];
            $product->save();
            return $this->responseSuccess(true,'Product Updated Successfully', $product,201);
        } catch (\Exception $e) {
            return $this->responseFail(false,$e->getMessage(),500);
        }
    }
    public function destroy($id){
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return $this->responseSuccess(true,'Product Deleted Successfully', $product,200);
        } catch (\Exception $e) {
            return $this->responseFail(false,$e->getMessage(),500);
        }
    }
}
