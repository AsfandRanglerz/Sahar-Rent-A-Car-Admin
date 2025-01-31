<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyPointRequest extends FormRequest
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
            'on_referal' => 'required',
            'on_car' => 'required',
            'discount' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'on_referal.required' => 'Referal Point is required',
            'on_car.required' => 'Car Rental Point is required',
            'discount.required' => 'Discount is required',
        ];
    }
}
