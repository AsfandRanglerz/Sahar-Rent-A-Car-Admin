<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|min:10',
                'password' => 'required|string|min:8',
                'image' => 'nullable|file|max:2048',
                'emirate_id' => 'required|file|mimes:jpeg,png,jpg, svg|max:2048',
                'emirate_id_back' => 'required|file|mimes:jpeg,png,jpg, svg|max:2048',
                'passport' => 'required|file|mimes:jpeg,png,jpg, svg|max:2048',
                'driving_license' => 'required|file|mimes:jpeg,png,jpg, svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
           'name.required' => 'The  Name is required',
    'name.string' => 'The  Name must be a string',
    'name.max' => 'The  Name must not exceed 255 characters',

    'email.required' => 'The  Email is required',
    'email.email' => 'The  Email must be a valid email address.',
    'email.unique' => 'The  Email has already been taken.',
    
    'phone.required' => 'The  Phone Number is required',
    'phone.string' => 'The  Phone Number must be a string',
    'phone.min' => 'The Phone Number must be at least 10 digits',
    
    'password.required' => 'The Password is required',
    'password.string' => 'The Password must be a string',
    'password.min' => 'The Password must be at least 8 characters',

    'emirate_id.required' => 'The Emirate ID is required',
    'emirate_id.file' => 'The Emirate ID must be a file.',
    'emirate_id.mimes' => 'The Emirate ID must be a file of type: jpeg, png, jpg, svg',
    'emirate_id.max' => 'The Emirate ID must not exceed size 2MB',

    'emirate_id_back.required' => 'The Emirate ID Back is required',
    'emirate_id_back.file' => 'The Emirate ID Back must be a file.',
    'emirate_id_back.mimes' => 'The Emirate ID Back must be a file of type: jpeg, png, jpg, svg',
    'emirate_id_back.max' => 'The Emirate ID Back must not exceed size 2MB',

    'passport.required' => 'The Passport is required',
    'passport.file' => 'The Passport must be a file.',
    'passport.mimes' => 'The Passport must be a file of type: jpeg, png, jpg, svg',
    'passport.max' => 'The Passport must not exceed size 2MB',

    'driving_license.required' => 'The Driving License is required',
    'driving_license.file' => 'The Driving License must be a file.',
    'driving_license.mimes' => 'The Driving License must be a file of type: jpeg, png, jpg, svg',
    'driving_license.max' => 'The Driving License must not exceed size 2MB',
        ];
    }
}
