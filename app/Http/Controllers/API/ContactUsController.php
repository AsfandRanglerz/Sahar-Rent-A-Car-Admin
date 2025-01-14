<?php

namespace App\Http\Controllers\API;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function contact(Request $request){
        $validate = Validator::make(
        $request->all(),    
        [
           'email' => 'required|email|unique:contact_us,email',
           'message' => 'required',
        ]
    );

    if($validate->fails()){
        return response ()->json([
            'status' => false,
            'message' => 'Validation Error',
            'error' => $validate->errors()->all()
        
        ], 401);
    }

    $contact = ContactUs::create([
        'email' => $request->email,
        'message' => $request->message,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Contact us successfull',
        'errors' => $contact
    ],200);
    }
}
