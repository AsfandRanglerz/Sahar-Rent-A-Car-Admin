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
                'password' => 'required|string|min:8',
                'image' => 'nullable|file|max:2048',
                'license' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
           'name.required' => 'The  Name is required.',
    'name.string' => 'The  Name must be a string.',
    'name.max' => 'The  Name must not exceed 255 characters.',

    // 'email.required' => 'The  Email is required.',
    // 'email.email' => 'The  Email must be a valid email address.',
    // 'email.unique' => 'The  Email has already been taken.',
    
    'phone.required' => 'The  Phone Number is required.',
    'phone.string' => 'The  Phone Number must be a string.',
    'phone.max' => 'The  Phone Number must not exceed 11 characters.',
    
    'password.required' => 'The Password is required.',
    'password.string' => 'The Password must be a string.',
    'password.min' => 'The Password must be at least 8 characters.',

    'license.required' => 'The License is required.',
    'license.mimes' => 'The License must be a file of type: jpeg, png, jpg, gif, svg.',
   
        ];
    }
}
