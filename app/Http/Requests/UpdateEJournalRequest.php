<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEJournalRequest extends FormRequest
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
        $eJournal = $this->route('e_journal');

        return [
            'e_issn' => 'required|unique:e_journals,e_issn,'.$eJournal,
            'editor.name' => 'required',
            'publisher.name' => 'required',
            'platform.name' => 'required',
            'accessModel.name' => 'required',
            'product.title' => 'required',
            'frequency' => 'required',
            'subscription_period' => 'required',
            'product.quantity' => 'required',
            'product.price' => 'required',
            'product.subject' => 'required',
            'image' => 'sometimes|required|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
