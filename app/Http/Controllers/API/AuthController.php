<?php

namespace App\Http\Controllers\API;


use App\Models\OTP;
use App\Models\User;
use App\Mail\OTPMail;
use App\Models\Driver;
use App\Models\Customer;
use App\Models\ForgotOTP;
use App\Mail\ForgotOTPMail;
use Illuminate\Support\Str;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use App\Models\DriverDocument;
use Illuminate\Support\Carbon;
use App\Models\driversregister;
use App\Models\LicenseApproval;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\CustomerRegisteredMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        // return "ok";
    $validateUser = Validator::make(
    $request->all(),
    [
    // 'name' => 'required|string|min:10',
    'email' => 'required|email|unique:users,email|unique:drivers,email',
    'phone' => 'required|numeric|min:7|unique:users,phone|unique:drivers,phone',
    // 'password' => 'required|min:6|confirmed',
    ]
);

if($validateUser->fails()){
return response()->json([
    // 'status' => false,
    'message' => $validateUser->errors()->first(),
    // 'errors' => $validateUser->errors()->all(),
],401);
}
$emirate_id = null;
$passport = null;
$driving_license = null;

if ($request->hasFile('emirate_id')) {
    $emirate_id = $request->file('emirate_id')->store("documents/emirate_id", 'public');
    // $document->emirate_id = "storage/app/public/{$path}";
}
if ($request->hasFile('passport')) {
    $passport = $request->file('passport')->store("documents/passport", 'public');
    // $document->passport = "storage/app/public/{$path}";
}
if ($request->hasFile('driving_license')) {
    $driving_license = $request->file('driving_license')->store("documents/driving_license", 'public');
    // $document->driving_license = "storage/app/public/{$path}";
}
$customer = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'phone' => $request->phone,
    'password' => bcrypt($request->password),
    'emirate_id' => $emirate_id,
    'passport' => $passport,
    'driving_license' => $driving_license,
]);

if ($request->hasFile('image')) {
    $file = $request->file('image');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('admin/assets/images/users'), $filename);
    $image = 'public/admin/assets/images/users/' . $filename;

    $customer->update(['image' => $image]);
}
// $document->save();
Mail::to($customer->email)->send(new CustomerRegisteredMail($customer->name, $customer->email, $customer->phone));
return response()->json([
    // 'status' => true,
    'message' => 'User created successfully',
    'data' => ['customer'=>$customer,
               
              ],
],200);
// $customer = Customer::create([
//     'name' => $request->name,
//     'email' => $request->email,
//     'phone' => $request->phone,
//     'password' => bcrypt($request->password),
// ]);


// if ($request->hasFile('profile_image')) {
//     $file = $request->file('profile_image');
//     $filename = time() . '_' . $file->getClientOriginalName();
//     $file->move(public_path('admin/assets/images/users'), $filename);
//     $image = 'public/admin/assets/images/users/' . $filename;

//     $customer->update(['profile_image' => $image]);
// }

// if(!$customer){
//     return response()->json([
//         // 'status' => false,
//         'message' => 'User not registered',
//         'errors' => $customer,
//     ],401);
//     }

// else{

// $document = UserDocument::firstOrCreate(['user_id' => $customer->id]);

// if ($request->hasFile('emirate_id')) {
//     $path = $request->file('emirate_id')->store("documents/{$customer->id}/emirate_id", 'public');
//     $document->emirate_id = "storage/app/public/{$path}";
// }
// if ($request->hasFile('passport')) {
//     $path = $request->file('passport')->store("documents/{$customer->id}/passport", 'public');
//     $document->passport = "storage/app/public/{$path}";
// }
// if ($request->hasFile('driving_license')) {
//     $path = $request->file('driving_license')->store("documents/{$customer->id}/driving_license", 'public');
//     $document->driving_license = "storage/app/public/{$path}";
// }

// $document->save();

// return response()->json([
//     // 'status' => true,
//     'message' => 'User created successfully',
//     'data' => ['customer'=>$customer,
//                'document'=>$document
//               ],
// ],200);
// }
}

public function driverregister(Request $request){
    // return "ok";
$validateUser = Validator::make(
$request->all(),
[
// 'name' => 'required|string|min:10',
'email' => 'required|email|unique:drivers,email|unique:users,email',
'phone' => 'required|numeric|min:7|unique:drivers,phone|unique:users,phone',
// 'password' => 'required|min:6|confirmed',
]
);

if($validateUser->fails()){
return response()->json([
// 'status' => false,
'message' => $validateUser->errors()->first(),
// 'errors' => $validateUser->errors()->all(),
],401);
}

$driver = Driver::create([
'name' => $request->name,
'email' => $request->email,
'phone' => $request->phone,
'password' => bcrypt($request->password),
]);

LicenseApproval::create([
    'driver_id' => $driver->id,
    'email' => $driver->email,
    'name' => $driver->name,
    // 'status' => 'pending', // No document yet
    // 'document_uploaded' => false, // New field to track document upload
]);

if ($request->hasFile('image')) {
    $file = $request->file('image');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('admin/assets/images/users'), $filename);
    $image = 'public/admin/assets/images/users/' . $filename;

    $driver->update(['image' => $image]);
}

// if(!$customer){
//     return response()->json([
//         // 'status' => false,
//         'message' => 'User not registered',
//         'errors' => $customer,
//     ],401);
//     }

// else{

// $drivers = $request->user();

// $document = DriverDocument::firstOrCreate(['driver_id' => $drivers->id]);

// if ($request->hasFile('license')) {
// $document->license = $request->file('license')->store("driverdocument/{$drivers->id}/license", 'public');
// }
       
// $document->save();
Mail::to($driver->email)->send(new CustomerRegisteredMail($driver->name, $driver->email, $driver->phone));     

return response()->json([
// 'status' => true,
'message' => 'Driver created successfully',
'data' => ['driver'=>$driver,
        //    'document'=>$document
          ],
],200);
// }
}

// public function uploadDocument(Request $request)
//     {
//         // $request->validate([
//         //     'emirate_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
//         //     'passport' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
//         //     'driving_license' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
//         // ]);

//         $user = $request->user();

//         $document = UserDocument::firstOrCreate(['user_id' => $user->id]);

        
//         if ($request->hasFile('emirate_id')) {
//             $document->emirate_id = $request->file('emirate_id')->store("documents/{$user->id}/emirate_id", 'public');
//         }
//         if ($request->hasFile('passport')) {
//             $document->passport = $request->file('passport')->store("documents/{$user->id}/passport", 'public');
//         }
//         if ($request->hasFile('driving_license')) {
//             $document->driving_license = $request->file('driving_license')->store("documents/{$user->id}/driving_license", 'public');
//         }

       
        
//         $document->save();
        

//         return response()->json(['message' => 'Documents uploaded successfully', 'data' => $document], 200);
//     }

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
            $licensePath = $request->file('license')->store("driverdocument/{$driver->id}/license", 'public');
        }
        $document->license = $licensePath;
        $document->save();

        LicenseApproval::where('driver_id', $request->driver_id)
        ->update(['image' => $licensePath]);
        

        return response()->json(['message' => 'Document uploaded successfully', 'data' => $document], 200);
    }

    public function login(Request $request){

        $validateUser = Validator::make(
            $request->all(),
            [
                // 'email' => 'required|email',
                'identifier' => 'required',
                'password' => 'required',
                'send_otp' => 'required',
                // 'fcm_token' => 'nullable|string',
            ],
            [
                'identifier.required' => 'The email or phone number is required.',
                'password.required' => 'The password is required.',
                'send_otp.required' => 'The otp field is required.',
            ]
        );
    
        if ($validateUser->fails()) {
            return response()->json([
                // 'status' => false,
                'message' => $validateUser->errors()->first(),
                // 'errors' => $validateUser->errors()->all(),
            ], 401);
        }
    
        // $customer = Customer::where('email', $request->email)->first();

        $identifier = $request->identifier;
        $customer = null;
    
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // If the identifier is an email
            $customer = User::where('email', $identifier)->first();
        } else {
            // If the identifier is a phone number
            $customer = User::where('phone', $identifier)->first();
        }
    
        if (!$customer) {
            return response()->json([
                // 'status' => false,
                'message' => 'Invalid email or phone',
            ], 404);
        }
    
        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                // 'status' => false,
                'message' => 'Invalid password',
            ], 401);
        }
        $otp = rand(100000, 999999);
    $otpToken = Str::uuid(); // Unique token for OTP verification
    // $expiresAt = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes

    // Store OTP in the database
    OTP::create([
        'identifier' => $identifier,
        'otp' => $otp,
        'otp_token' => $otpToken,
        // 'expires_at' => $expiresAt,
    ]);

    // Send OTP via email (or SMS)
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        Mail::to($identifier)->send(new OTPMail($otp));
    }

    // return response()->json([
    //     'message' => 'OTP sent successfully.',
    //     'otp_token' => $otpToken, // Send OTP token to frontend
    // ], 200);
    
        if ($request->fcm_token) {
            $customer->update(['fcm_token' => $request->fcm_token]);
        }

        return response()->json([
            // 'status' => true,
            // 'message' => 'Logged In successfully',
            'message' => 'OTP sent successfully.',
            // 'token' => $customer->createToken("API Token")->plainTextToken,
            'otp_token' => $otpToken, // Send OTP token to frontend 
            'fcm_token' => $customer->fcm_token,
            // 'token_type' => 'Bearer',
        ], 200);
        
        
    }

    public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
        'otp_token' => 'required'
    ]);

    // Find OTP record in database
    $otpRecord = OTP::where('otp_token', $request->otp_token)->first();

    if (!$otpRecord) {
        return response()->json(['message' => 'Invalid OTP token.'], 400);
    }

    // Check if OTP is valid
    if ($otpRecord->otp !== $request->otp) {
        return response()->json(['message' => 'Invalid OTP.'], 401);
    }

    // Check if OTP has expired
    // if (Carbon::now()->gt($otpRecord->expires_at)) {
    //     return response()->json(['message' => 'OTP has expired.'], 401);
    // }

    // Retrieve user (optional: if logging in)
    $customer = User::where('email', $otpRecord->identifier)
                        // ->orWhere('phone', $otpRecord->identifier)
                        ->first();

    if (!$customer) {
        return response()->json(['message' => 'User not found.'], 404);
    }
    Auth::login($customer);

   
    // Delete OTP after successful verification
    $otpRecord->delete();

    return response()->json([
        'message' => 'OTP verified successfully',
        'token' => $customer->createToken("API Token")->plainTextToken,
        'user' => [
        'name' => $customer->name,
        'email' => $customer->email,
        'phone' => $customer->phone,
        'image' => $customer->image, // Assuming there's a profile image field
        'emarate_id' => $customer->emirate_id,
        'passport' => $customer->passport,
        'driving_license' => $customer->driving_license,
       
    ]
    ], 200);
}

    

    public function driverlogin(Request $request){

        $validateUser = Validator::make(
            $request->all(),
            [
                // 'email' => 'required|email',
                'identifier' => 'required',
                'password' => 'required',
                'send_otp' => 'required',
            ],
            [
                'identifier.required' => 'The email or phone number is required.',
                'password.required' => 'The password is required.',
                'send_otp.required' => 'The otp field is required.',
            ]
        );
    
        if ($validateUser->fails()) {
            return response()->json([
                // 'status' => false,
                'message' => $validateUser->errors()->first(),
                // 'errors' => $validateUser->errors()->all(),
            ], 401);
        }
    
        // $customer = Customer::where('email', $request->email)->first();

        $identifier = $request->identifier;
        $driver = null;
    
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // If the identifier is an email
            $driver = Driver::where('email', $identifier)->first();
        } else {
            // If the identifier is a phone number
            $driver = Driver::where('phone', $identifier)->first();
        }

        if (!$driver) {
            return response()->json([
                // 'status' => false,
                'message' => 'Invalid email or phone',
            ], 404);
        }
    
        if (!Hash::check($request->password, $driver->password)) {
            return response()->json([
                // 'status' => false,
                'message' => 'Invalid password',
            ], 401);
        }
        $otp = rand(100000, 999999);
        $otpToken = Str::uuid(); // Unique token for OTP verification
        // $expiresAt = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes
    
        // Store OTP in the database
        OTP::create([
            'identifier' => $identifier,
            'otp' => $otp,
            'otp_token' => $otpToken,
            // 'expires_at' => $expiresAt,
        ]);
    
        // Send OTP via email (or SMS)
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            Mail::to($identifier)->send(new OTPMail($otp));
        }
    
       
        
            if ($request->fcm_token) {
                $customer->update(['fcm_token' => $request->fcm_token]);
            }
    
        // if (!$customer) {
        //     return response()->json([
        //         // 'status' => false,
        //         'message' => 'Invalid email or phone',
        //     ], 404);
        // }
    
        // if (!Hash::check($request->password, $customer->password)) {
        //     return response()->json([
        //         // 'status' => false,
        //         'message' => 'Invalid password',
        //     ], 401);
        // }
    
        return response()->json([
            // 'status' => true,
            // 'message' => 'Logged In successfully',
            'message' => 'OTP sent successfully.',
            'otp_token' => $otpToken, // Send OTP token to frontend 
            'fcm_token' => $driver->fcm_token,
            'token' => $driver->createToken("API Token")->plainTextToken,
            // 'token_type' => 'Bearer',
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
        // 'status' => true,
        'message' => 'Logged Out successfully',
    ], 200);
    }

public function forgotPassword(Request $request)
{
    // Validate the email input
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $email = $request->email;

    // Generate a unique token
    $otp = rand(100000, 999999);
    $otpToken = Str::uuid(); // Unique token for OTP verification
    // $expiresAt = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes

    // Store OTP in the database
    ForgotOTP::create([
        'identifier' => $request->email,
        'otp' => $otp,
        'otp_token' => $otpToken,
        // 'expires_at' => $expiresAt,
    ]);

    // Send OTP via email (or SMS)
    // if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        Mail::to($email)->send(new ForgotOTPMail($otp));
    // }

    return response()->json([
        'message' => 'OTP sent successfully.',
        'otp_token' => $otpToken, // Send OTP token to frontend
    ], 200);
}

public function forgotverifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
        // 'otp_token' => 'required'
    ]);

    // Find OTP record in database
    $otpRecord = ForgotOTP::where('otp_token', $request->otp_token)->first();

    if (!$otpRecord) {
        return response()->json(['message' => 'Invalid OTP token.'], 400);
    }

    // Check if OTP is valid
    if ($otpRecord->otp !== $request->otp) {
        return response()->json(['message' => 'Invalid OTP.'], 401);
    }

    // Check if OTP has expired
    // if (Carbon::now()->gt($otpRecord->expires_at)) {
    //     return response()->json(['message' => 'OTP has expired.'], 401);
    // }

    // Retrieve user (optional: if logging in)
    $user = User::where('email', $otpRecord->identifier)
                        // ->orWhere('phone', $otpRecord->identifier)
                        ->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    
    $otpRecord->update(['verified' => true]);

    return response()->json([
        'message' => 'OTP verified successfully',
        'otp_token' => $otpRecord->otp_token,
        // 'token' => $user->createToken("API Token")->plainTextToken,
    ], 200);
}

public function resetPassword(Request $request)
{
    // Validate the input
    $request->validate([
        'otp_token' => 'required|uuid',
        'new_password' => 'required|string|min:6|confirmed',
    ]);

    // Fetch OTP record using otp_token
    $otpRecord = ForgotOTP::where('otp_token', $request->otp_token)->first();

    if (!$otpRecord || !$otpRecord->verified) {
        return response()->json(['message' => 'Invalid or unverified OTP token'], 400);
    }

    // Find the user
    $user = User::where('email', $otpRecord->identifier)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Update password
    $user->update(['password' => Hash::make($request->new_password)]);

    // Delete OTP record after successful password reset
    $otpRecord->delete();

    return response()->json([
        'message' => 'Password reset successfully.',
    ], 200);
}

public function getProfile(Request $request)
    {
        $customer = Auth::user();

        return response()->json([
            // 'status' => true,
            'message' => 'User profile retrieved successfully.',
            'data' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'image' => $customer->image, 
                'emirate_id' => $customer->emirate_id,
                'passport' => $customer->passport,
                'driving_license' => $customer->driving_license,
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
            // 'email' => 'nullable|email|unique:users,email|unique:drivers,email,' . $customer->id, 
            // 'phone' => 'nullable|string|unique:users,phone|unique:drivers,phone,' . $customer->id, 
        //     'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        // ]);

        // if($validator->fails()){
        //     return response()->json([
        //         // 'status' => false,
        //         'message' => $validator->errors()->first(),
        //         // 'errors' => $validateUser->errors()->all(),
        //     ],401);
        //     }
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
        // $emirate_id = null;
        // $passport = null;
        // $driving_license = null;
        
        // if ($request->hasFile('emirate_id')) {
        //     $emirate_id = $request->file('emirate_id')->store("documents/emirate_id", 'public');
        //     $customer->emirate_id = "{$emirate_id}";
        // }
        // if ($request->hasFile('passport')) {
        //     $passport = $request->file('passport')->store("documents/passport", 'public');
        //     $customer->passport = "{$passport}";
        // }
        // if ($request->hasFile('driving_license')) {
        //     $driving_license = $request->file('driving_license')->store("documents/driving_license", 'public');
        //     $customer->driving_license = "{$driving_license}";
        // }
        // Handle profile image upload
        $customer->name = $request->name ?? $customer->name;
        $customer->email = $request->email ?? $customer->email;
        $customer->phone = $request->phone ?? $customer->phone; // Keep existing phone if not provided

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        
            $customer->update(['image' => $image]);
        }
    
        $customer->save();
    
        return response()->json([
            // 'status' => true,
            'message' => 'Customer profile updated successfully.',
            'data' => [
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'image' => $customer->image ? asset($customer->image) : null,
                // 'emirate_id' => $emirate_id,
                // 'passport' => $passport,
                // 'driving_license' => $driving_license,

            ],
        ], 200);
    }
    
    public function updateDocument(Request $request)
    {


        // Get the authenticated user
        $customer = Auth::user();
        
        // Validate the request
        // $validator = Validator::make($request->all(), [
        // //     'name' => 'required|string|max:255',
        //     'email' => 'nullable|email|unique:users,email|unique:drivers,email,' . $customer->id, 
        //     'phone' => 'nullable|string|unique:users,phone|unique:drivers,phone,' . $customer->id, 
        // //     'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        // ]);

        // if($validator->fails()){
        //     return response()->json([
        //         // 'status' => false,
        //         'message' => $validator->errors()->first(),
        //         // 'errors' => $validateUser->errors()->all(),
        //     ],401);
        //     }
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

        // $customer->name = $request->name;
        // $customer->email = $request->email;
        // $customer->phone = $request->phone;
        $emirate_id = null;
        $passport = null;
        $driving_license = null;
        
        if ($request->hasFile('emirate_id')) {
            $emirate_id = $request->file('emirate_id')->store("documents/emirate_id", 'public');
            $customer->emirate_id = "{$emirate_id}";
        }
        if ($request->hasFile('passport')) {
            $passport = $request->file('passport')->store("documents/passport", 'public');
            $customer->passport = "{$passport}";
        }
        if ($request->hasFile('driving_license')) {
            $driving_license = $request->file('driving_license')->store("documents/driving_license", 'public');
            $customer->driving_license = "{$driving_license}";
        }
        // Handle profile image upload
        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('admin/assets/images/users'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        
        //     $customer->update(['image' => $image]);
        // }
    
        $customer->save();
    
        return response()->json([
            // 'status' => true,
            'message' => 'Customer document updated successfully.',
            'data' => [
                // 'name' => $customer->name,
                // 'email' => $customer->email,
                // 'phone' => $customer->phone,
                // 'image' => $customer->image ? asset($customer->image) : null,
                'emirate_id' => $emirate_id,
                'passport' => $passport,
                'driving_license' => $driving_license,

            ],
        ], 200);
    }
    
}
