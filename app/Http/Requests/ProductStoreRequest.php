<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->isMethod('POST')){
            return [
                "name" => "required|string|max:255",
                "image" => "required|image|mimes:jpeg,jpg,png,webp,gif|max:2048",
                "description" => "required",
                'category_id' => 'required|exists:categories,id',
            ];
        }
        return [];
    }
}
