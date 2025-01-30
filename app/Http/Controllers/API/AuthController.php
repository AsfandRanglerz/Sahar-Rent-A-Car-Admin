<?php

namespace App\Http\Controllers\API;


use App\Models\Customer;
use Illuminate\Support\Str;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use App\Models\DriverDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        // return "ok";
    $validateUser = Validator::make(
    $request->all(),
    [
    'name' => 'required|string|min:10',
    'email' => 'required|email|unique:customers,email',
    'phone' => 'required|numeric',
    'password' => 'required|min:6|confirmed',
    ]
);

if($validateUser->fails()){
return response()->json([
    'status' => false,
    'message' => 'Validation Error',
    'errors' => $validateUser->errors()->all()
],401);
}

$customer = Customer::create([
    'name' => $request->name,
    'email' => $request->email,
    'phone' => $request->phone,
    'password' => bcrypt($request->password)
]);

return response()->json([
    'status' => true,
    'message' => 'User created successfully',
    'errors' => $customer,
],200);
}

public function uploadDocument(Request $request)
    {
        // $request->validate([
        //     'emirate_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        //     'passport' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        //     'driving_license' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        // ]);

        $user = $request->user();

        $document = UserDocument::firstOrCreate(['user_id' => $user->id]);

        
        // if ($request->hasFile('emirate_id')) {
        //     $document->emirate_id = $request->file('emirate_id')->store("documents/{$user->id}/emirate_id", 'public');
        // }
        // if ($request->hasFile('passport')) {
        //     $document->passport = $request->file('passport')->store("documents/{$user->id}/passport", 'public');
        // }
        // if ($request->hasFile('driving_license')) {
        //     $document->driving_license = $request->file('driving_license')->store("documents/{$user->id}/driving_license", 'public');
        // }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('storage/documents/'), $filename);
            $image = 'public/storage/documents/images/users/' . $filename;
        } 
        
        $document->save();
        

        return response()->json(['message' => 'Documents uploaded successfully', 'data' => $document], 200);
    }

    public function driverdocument(Request $request)
    {
        // $request->validate([
        //     'emirate_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        //     'passport' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        //     'driving_license' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        // ]);

        $driver = $request->user();

        $document = DriverDocument::firstOrCreate(['driver_id' => $driver->id]);

        if ($request->hasFile('license')) {
            $document->license = $request->file('license')->store("driverdocument/{$driver->id}/license", 'public');
        }
       
        
        $document->save();
        

        return response()->json(['message' => 'Document uploaded successfully', 'data' => $document], 200);
    }

    public function login(Request $request){

        $validateUser = Validator::make(
            $request->all(),
            [
                // 'email' => 'required|email',
                'identifier' => 'required',
                'password' => 'required',
            ]
        );
    
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'errors' => $validateUser->errors()->all(),
            ], 401);
        }
    
        // $customer = Customer::where('email', $request->email)->first();

        $identifier = $request->identifier;
        $customer = null;
    
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // If the identifier is an email
            $customer = Customer::where('email', $identifier)->first();
        } else {
            // If the identifier is a phone number
            $customer = Customer::where('phone', $identifier)->first();
        }
    
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or phone',
            ], 404);
        }
    
        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid password',
            ], 401);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Logged In successfully',
            'token' => $customer->createToken("API Token")->plainTextToken,
            'token_type' => 'Bearer',
        ], 200);
        
        
    }

    public function logout(Request $request){
        // $customer = $request->customer();
        // $customer->tokens()->delete();

        // return response()->json([
        //     'status' => true,
        //     'customer' => $customer,
        //     'message' => 'Logged Out successfully',
        // ],200);

        $customer = $request->user();
        $customer->tokens()->delete();      // Revoke all tokens associated with the customer

    return response()->json([
        'status' => true,
        'message' => 'Logged Out successfully',
    ], 200);
    }

public function forgotPassword(Request $request)
{
    // Validate the email input
    $request->validate([
        'email' => 'required|email|exists:customers,email',
    ]);

    // Generate a unique token
    $token = Str::random(64);

    // Store the reset token in the password_resets table
    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        [
            'token' => $token,
            'created_at' => Carbon::now(),
        ]
    );

    // Send the reset link to the user
    Mail::send('admin.emails.forgot-password', ['token' => $token], function ($message) use ($request) {
        $message->to($request->email);
        $message->subject('Reset Password Notification');
    });

    return response()->json([
        'status' => true,
        'message' => 'Password reset link has been sent to your email.',
    ], 200);
}

public function resetPassword(Request $request)
{
    // Validate the input
    $request->validate([
        'email' => 'required|email|exists:customers,email',
        'token' => 'required',
        'password' => 'required|confirmed|min:6',
    ]);

    // Find the reset token in the database
    $resetRecord = DB::table('password_resets')->where([
        ['email', '=', $request->email],
        ['token', '=', $request->token],
    ])->first();

    if (!$resetRecord) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid token or email.',
        ], 400);
    }

    // Check if the token is expired (valid for 60 minutes)
    $isExpired = Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast();
    if ($isExpired) {
        return response()->json([
            'status' => false,
            'message' => 'This token has expired.',
        ], 400);
    }

    // Update the user's password
    $customer = Customer::where('email', $request->email)->first();
    $customer->password = Hash::make($request->password);
    $customer->save();

    // Delete the reset token
    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json([
        'status' => true,
        'message' => 'Password has been reset successfully.',
    ], 200);
}

public function getProfile(Request $request)
    {
        $customer = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully.',
            'data' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'profile_image' => $customer->profile_image, // Assuming there's a profile image field
            ],
        ], 200);
    }

    
    public function updateProfile(Request $request)
    {


        // Get the authenticated user
        $customer = Auth::user();
        
        // Validate the request
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:customers,email,' . $customer->id, 
        //     'phone' => 'nullable|string|unique:customers,phone,' . $customer->id, 
        //     'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        // ]);
    
        // // Log the incoming data for debugging
        // \Log::info($request->all());
    
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Validation errors.',
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }
    
        // Update customer details
        // return $customer;

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
    
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'admin/assets/images/users/' . $filename;

            // Store file manually in the public folder
            $file->move(public_path('admin/assets/images/users'), $filename);

            $customer->profile_image = $path;
        }
    
        $customer->save();
    
        return response()->json([
            'status' => true,
            'message' => 'Customer profile updated successfully.',
            'data' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'profile_image' => $customer->profile_image ? asset($customer->profile_image) : null,
            ],
        ], 200);
    }
    
    
}
