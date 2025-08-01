<?php

namespace App\Http\Controllers\API;

use App\Models\Contact;
use App\Models\ContactUs;
use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
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

    $contact = Contact::create([
        'email' => $request->email,
        'message' => $request->message,
    ]);

    $adminEmail = ContactUs::value('email');
    Mail::to($adminEmail)->send(new ContactUsMail($contact->email, $contact->message));
    return response()->json([
        // 'status' => true,
        'message' => 'Your message has been sent successfully.',
        'data' => $contact
    ],200);
    }

    public function contactUs()
    {
        $contact = ContactUs::select('email', 'phone', 'address')->first();
 
        return response()->json([
            'message' => 'Contact Us details retrieved successfully',
            'data' => $contact
        ]);
    }
}
