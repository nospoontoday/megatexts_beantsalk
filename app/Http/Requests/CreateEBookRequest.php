<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEBookRequest extends FormRequest
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
            'e_isbn' => 'required|unique:e_books,e_isbn',
            'author' => 'required',
            'publisher' => 'required',
            'title' => 'required',
            'publication_year' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'subject' => 'required',
            'platform' => 'required',
            'access_model' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
