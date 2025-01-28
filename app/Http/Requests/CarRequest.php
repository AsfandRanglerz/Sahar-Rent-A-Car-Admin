<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'car_name' => 'required|string|max:255',
            'sanitized' => 'required|numeric',
            'car_feature' => 'required|numeric',
            'passengers' => 'required|numeric|max:10',
            'luggage' => 'required|numeric|max:255',
            'doors' => 'required|numeric|max:10',
            'car_type' => 'required|string|max:255',
            // 'call_number' => 'required|numeric|digits:11',
            // 'whatsapp_number' => 'required|numeric|digits:11',
            'pricing' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }

    public function messages(): array
    {
        return [
           'car_name.required' => 'The car name is required.',
    'car_name.string' => 'The car name must be a string.',
    'car_name.max' => 'The car name must not exceed 255 characters.',
    
    'sanitized.required' => 'Price Per Day is required.',
    'sanitized.numeric' => 'Price Per Day must be a number.',
    'sanitized.max' => 'Price Per Day must not exceed 255.',
    
    'car_feature.required' => 'Price Per Week is required.',
    'car_feature.numeric' => 'Price Per Week must be a number.',
    'car_feature.max' => 'Price Per Week value must not exceed 255.',
    
    'passengers.required' => 'The number of Passengers is required.',
    'passengers.numeric' => 'The number of Passengers must be a number.',
    'passengers.max' => 'The number of Passengers must not exceed 10.',
    
    'luggage.required' => 'The Luggage capacity is required.',
    'luggage.numeric' => 'The Luggage capacity must be a number.',
    'luggage.max' => 'The Luggage capacity must not exceed 255.',
    
    'doors.required' => 'The number of Doors is required.',
    'doors.numeric' => 'The number of Doors must be a number.',
    'doors.max' => 'The number of Doors must not exceed 10.',
    
    'car_type.required' => 'The Car type is required.',
    'car_type.string' => 'The Car type must be a string.',
    'car_type.max' => 'The Car type must not exceed 255 characters.',
    
    // 'call_number.required' => 'The Phone number is required.',
    // 'call_number.numeric' => 'The Phone number must be a valid number.',
    // 'call_number.min' => 'The Phone number must be at least 15 digits.',
    
    // 'whatsapp_number.required' => 'The WhatsApp number is required.',
    // 'whatsapp_number.numeric' => 'The WhatsApp number must be a valid number.',
    // 'whatsapp_number.min' => 'The WhatsApp number must be at least 15 digits.',
    
    'pricing.required' => 'The Price Per Hour is required.',
    'pricing.regex' => 'The Price Per Hour must be a valid number with up to two decimal places.',
        ];
    }
}
