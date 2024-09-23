<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "image" => $this->getImageData(),
        ];
    }
    private function getImageData(){
        if($this->image){
            return [
                "image" => asset("/storage/categories/". $this->image->image),
                "image_name" => $this->image->image_name,
            ];
        }
    }
}
