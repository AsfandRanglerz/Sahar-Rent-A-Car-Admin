<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
            
                'name' => 'required|string|max:255',
                // 'email' => 'required|email|unique:drivers,email',
                'phone' => 'required|string|max:11',
            
        ];
    }

    public function messages(): array
    {
        return [
           'name.required' => 'The  Name is required.',
    'name.string' => 'The  Name must be a string.',
    'name.max' => 'The  Name must not exceed 255 characters.',
    
    'phone.required' => 'The  Phone Number is required.',
    'phone.string' => 'The  Phone Number must be a string.',
    'phone.max' => 'The  Phone Number must not exceed 11 characters.',
    
    
   
        ];
    }
}
