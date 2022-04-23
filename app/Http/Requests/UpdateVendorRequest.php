<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
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
        $vendor = $this->route('vendor');

        return [
            'name' => 'required|unique:vendors,name,' .$vendor,
            'contact_person' => 'required',
            'contact.*' => 'required',
            'presentAddress.*' => 'required',
        ];
    }
}
