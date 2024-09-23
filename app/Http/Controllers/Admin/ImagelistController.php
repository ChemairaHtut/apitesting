<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Http\Traits\ApiUseTrait;
use App\Http\Resources\ImageResource;

class ImagelistController extends Controller
{
    use ApiUseTrait;
    public function lists(Request $request){
        try {
            $images = Image::paginate(($request->per_page) ? $request->per_page : 5);
            $data['data'] = ImageResource::collection($images);
            $data['links'] = [
                'first' => $images->url(1),
                'last' => $images->url($images->lastPage()),
                'prev' => $images->previousPageUrl(),
                'next' => $images->nextPageUrl(),
            ];
            $data['meta'] = [
                'current_page' => $images->currentPage(),
                'from' => $images->firstItem(),
                'last_page' => $images->lastPage(),
                'path' =>  $images->url($images->currentPage()),
                'per_page' => $request->per_page,
                'to'=> $images->lastItem(),
                'total' => $images->total(),
            ];
            return $this->responseSuccess(true, "All Images List", (object)$data,200);
        } catch (\Exception $e) {
            return $this->responseFail(false,$e->getMessage() ,500);
        }

    }
}
