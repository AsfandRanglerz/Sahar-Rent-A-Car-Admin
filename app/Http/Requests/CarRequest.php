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
            'price_per_week' => 'required|numeric',
            'price_per_two_week' => 'required|numeric',
            'price_per_three_week' => 'required|numeric',
            'price_per_month' => 'required|numeric',
            'passengers' => 'required|numeric|max:10',
            'luggage' => 'required|numeric|max:255',
            'doors' => 'required|numeric|max:10',
            'car_type' => 'required|string|max:255',
            'call_number' => 'required|string|min:10',
            // 'whatsapp_number' => 'required|numeric|digits:11',
            'price_per_day' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'image' => 'required|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
           'car_name.required' => 'The Car Name is required',
    'car_name.string' => 'The Car Name must be a string',
    'car_name.max' => 'The Car Name must not exceed 255 characters',
    
    'price_per_week.required' => 'Price per day is required',
    'price_per_week.numeric' => 'Price per day must be a number',
    'price_per_week.max' => 'Price per day must not exceed 255',

    'price_per_two_week.required' => 'Price per two weeks is required',
    'price_per_two_week.numeric' => 'Price per two weeks must be a number',

    'price_per_three_week.required' => 'Price per three weeks is required',
    'price_per_three_week.numeric' => 'Price per three weeks must be a number',
    
    'price_per_month.required' => 'Price per week is required',
    'price_per_month.numeric' => 'Price per week must be a number',
    'price_per_month.max' => 'Price per week value must not exceed 255',
    
    'passengers.required' => 'The Number of passengers is required',
    'passengers.numeric' => 'The Number of passengers must be a number',
    'passengers.max' => 'The Number of passengers must not exceed 10',
    
    'luggage.required' => 'The Luggage capacity is required',
    'luggage.numeric' => 'The Luggage capacity must be a number',
    'luggage.max' => 'The Luggage capacity must not exceed 255',
    
    'doors.required' => 'The Number of doors is required',
    'doors.numeric' => 'The Number of doors must be a number',
    'doors.max' => 'The Number of doors must not exceed 10',
    
    'car_type.required' => 'The Car Type is required',
    'car_type.string' => 'The Car Type must be a string',
    'car_type.max' => 'The Car Type must not exceed 255 characters',
    
    'call_number.required' => 'The Phone Number is required',
    'call_number.numeric' => 'The Phone Number must be a valid number.',
    'call_number.min' => 'The Phone Number must be at least 10 digits',
    
    // 'whatsapp_number.required' => 'The WhatsApp number is required',
    // 'whatsapp_number.numeric' => 'The WhatsApp number must be a valid number.',
    // 'whatsapp_number.min' => 'The WhatsApp number must be at least 15 digits.',
    
    'price_per_day.required' => 'The Price Per Hour is required',
    'price_per_day.regex' => 'The Price Per Hour must be a valid number with up to two decimal places',

    'image.required' => 'The Image is required',
    'image.max' => 'The Image must not exceed size 2MB',

        ];
    }
}
