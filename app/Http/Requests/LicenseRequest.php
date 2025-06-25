<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|mimes:jpeg,png,jpg, svg',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'The License Image is required',
            // 'image.image' => 'The License Image must be an image.',
            'image.mimes' => 'The License Image must be a file of type: jpeg, png, jpg, svg',
            // 'image.max' => 'The License Image must not be greater than 2048 kilobytes.',
        ];
    }
}
