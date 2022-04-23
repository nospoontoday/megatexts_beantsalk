<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAVMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_code' => 'required|unique:a_v_materials,item_code',
            'author' => 'required',
            'publisher' => 'required',
            'title' => 'required',
            'publication_year' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'subject' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
