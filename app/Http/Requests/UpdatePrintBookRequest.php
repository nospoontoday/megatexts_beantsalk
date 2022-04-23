<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrintBookRequest extends FormRequest
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
        $printBook = $this->route('print_book');

        return [
            'isbn_13' => 'required|unique:print_books,isbn_13,'.$printBook,
            'author.name' => 'required',
            'publisher.name' => 'required',
            'product.title' => 'required',
            'publication_year' => 'required',
            'product.quantity' => 'required',
            'product.price' => 'required',
            'product.subject' => 'required',
            'discount' => 'sometimes|required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
