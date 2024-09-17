<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(){
        try {
            $categories = Category::all();
            return response()->json([
                'success' => true,
                'data' => $categories
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'something went wrong'
            ],500);
        }
    }
    public function create(){
        return "Lee Lar";
    }
    public function store(CategoryStoreRequest $request){
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
            Category::create([
                "name"=> $request->name,
                "image"=>$imageName,
            ]);
            $request->image->storeAs('categories', $imageName, 'public');
            return response()->json([
                "message"=>"Category Created Successfully",
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'something went wrong'
            ],500);
        }
    }
    public function edit($id){
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $category
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found'
            ],500);
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
                if($category->image){
                    Storage::disk('public')->delete('categories/'.$category->image);
                }
                $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
                $request->image->storeAs('categories',$imageName,'public');
                $category->image = $imageName;
            }
            $category->name = $validate['name'];
            $category->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Category Updated Successfully',
                'data' => $category
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong' 
            ],500);
        }
    }
    public function destroy($id){
        try{
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category Deleted Successfully',
            ],200);
        }catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong',
            ],500);
        }
    }
}
