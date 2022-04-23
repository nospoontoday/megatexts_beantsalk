<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibraryFixtureRequest extends FormRequest
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
        $libraryFixture = $this->route('library_fixture');

        return [
            'item_code' => 'required|unique:library_fixtures,item_code,'.$libraryFixture,
            'manufacturer.name' => 'required',
            'product.title' => 'required',
            'dimension' => 'required',
            'product.quantity' => 'required',
            'product.price' => 'required',
            'product.subject' => 'required',
            'vatable_sales' => 'required',
            'vat' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
