<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibraryTechnologyRequest extends FormRequest
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
        $libraryTechnology = $this->route('library_technology');

        return [
            'item_code' => 'required|unique:library_technologies,item_code,'.$libraryTechnology,
            'developer.name' => 'required',
            'product.title' => 'required',
            'subscription_period' => 'required',
            'product.quantity' => 'required',
            'product.price' => 'required',
            'product.subject' => 'required',
            'vatable_sales' => 'required',
            'vat' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
