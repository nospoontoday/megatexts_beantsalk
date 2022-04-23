<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEBookRequest extends FormRequest
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
        $eBook = $this->route('e_book');

        return [
            'e_isbn' => 'required|unique:e_books,e_isbn,'.$eBook,
            'author.name' => 'required',
            'publisher.name' => 'required',
            'platform.name' => 'required',
            'accessModel.name' => 'required',
            'product.title' => 'required',
            'publication_year' => 'required',
            'product.quantity' => 'required',
            'product.price' => 'required',
            'product.subject' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
