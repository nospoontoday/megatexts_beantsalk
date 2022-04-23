<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEJournalRequest extends FormRequest
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
            'e_issn' => 'required|unique:e_journals,e_issn',
            'publisher' => 'required',
            'editor' => 'required',
            'title' => 'required',
            'frequency' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'platform' => 'required',
            'access_model' => 'required',
            'subscription_period' => 'required',
            'subject' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
