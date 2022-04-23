<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
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
            'project_title' => 'required',
            'pr_number' => 'required|unique:quotations,pr_number',
            'deadline' => 'required',
            'bidding_date' => 'required',
            'terms_conditions' => 'sometimes|required',
            'purpose_id' => 'sometimes|required',
            'productQuotations.*' => 'sometimes|required',
        ];
    }
}
