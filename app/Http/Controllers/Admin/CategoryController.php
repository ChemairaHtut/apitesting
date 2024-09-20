<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Traits\ApiUseTrait;
use App\Http\Traits\FileUploadTrait;

class CategoryController extends Controller
{
    use ApiUseTrait,FileUploadTrait;
    public function index(Request $request){
        try {
            $categories = Category::paginate(($request->per_page) ? $request->per_page : 5);
            $paginateData = CategoryResource::collection($categories)->response()->getData(true);
            return $this->responseSuccess(true, "All Categories List", (object)$paginateData,200);
        } catch (\Exception $e) {
            return $this->responseFail(false,$e->getMessage() ,500);
        }
    }
    
    public function store(CategoryStoreRequest $request){
        try {
            $fileName = $this->uploadFile($request->image, 'categories');
            Category::create([
                "name"=> $request->name,
                "image"=> $fileName,
            ]);
            
            return $this->responseSuccess(true,'Category Created Successfully',null,201);
        } catch (\Exception $e) {
            return $this->responseFail(false,$e->getMessage() ,500);
        }
        
    }
    public function edit($id){
        try {
            $category = new CategoryResource(Category::findOrFail($id));
            return $this->responseSuccess(true,'Category List',$category,200);

        } catch (\Exception $e) {
            return $this->responseFail(false,$e->getMessage() ,500);
        }
    }
    public function update(Request $request, $id){
        $validate = $request->validate([
            "name" => "required|string|max:255",
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);
        try {
            $category = Category::findOrFail($id);
            if($request->hasFile('image')){
                $imageName = $this->UpdateImage($request->image,'categories',$category->image);
                $category->image = $imageName;
            }
            $category->name = $validate['name'];
            $category->save();
            return $this->responseSuccess(true,'Category Updated Successfully',null,201);
        } catch (\Exception $e) {
            return $this->responseFail(false,$e->getMessage() ,500);
        }
    }
    public function destroy($id){
        try{
            $category = Category::findOrFail($id);
            $category->delete();

            return $this->responseSuccess(true,'Category Deleted Successfully',null,200);
        }catch (\Throwable $th) {
            return $this->responseFail(false,$th->getMessage() ,500);
        }
    }
}
