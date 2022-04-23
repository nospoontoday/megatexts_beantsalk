<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLibraryFixtureRequest extends FormRequest
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
            'item_code' => 'required|unique:library_fixtures,item_code',
            'manufacturer' => 'required',
            'title' => 'required',
            'dimension' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'vatable_sales' => 'required',
            'vat' => 'required',
            'subject' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
