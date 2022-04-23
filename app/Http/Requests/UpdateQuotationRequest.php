<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuotationRequest extends FormRequest
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
            'deadline' => 'required',
            'bidding_date' => 'required',
            'terms_conditions' => 'sometimes|required',
            'purpose_id'    => 'required',
        ];
    }
}
