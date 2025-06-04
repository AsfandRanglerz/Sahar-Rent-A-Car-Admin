<?php

namespace App\Http\Controllers\API;


use Exception;
use Carbon\Carbon;
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
use App\Mail\UserCredentials;
use App\Models\DeleteRequest;
use App\Models\LoyaltyPoints;
use App\Models\DriverDocument;
use App\Mail\DriverCredentials;
use App\Models\DriverForgotOTP;
use App\Models\driversregister;
use App\Models\LicenseApproval;
use App\Models\DriverNotification;
use App\Models\UserLoyaltyEarning;
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

// if ($request->hasFile('emirate_id')) {
//     $emirate_id = $request->file('emirate_id')->store("documents/emirate_id", 'public');
//     // $document->emirate_id = "storage/app/public/{$path}";
// }
// if ($request->hasFile('passport')) {
//     $passport = $request->file('passport')->store("documents/passport", 'public');
//     // $document->passport = "storage/app/public/{$path}";
// }
// if ($request->hasFile('driving_license')) {
//     $driving_license = $request->file('driving_license')->store("documents/driving_license", 'public');
//     // $document->driving_license = "storage/app/public/{$path}";
// }
if ($request->hasFile('emirate_id')) {
    $file = $request->file('emirate_id');
    $filename = time() . '_emirate_id_' . $file->getClientOriginalName();
    $file->move(public_path('admin/assets/images/users'), $filename);
    $emirate_id = 'public/admin/assets/images/users/' . $filename;
}

if ($request->hasFile('passport')) {
    $file = $request->file('passport');
    $filename = time() . '_passport_' . $file->getClientOriginalName();
    $file->move(public_path('admin/assets/images/users'), $filename);
    $passport = 'public/admin/assets/images/users/' . $filename;
}

if ($request->hasFile('driving_license')) {
    $file = $request->file('driving_license');
    $filename = time() . '_driving_license_' . $file->getClientOriginalName();
    $file->move(public_path('admin/assets/images/users'), $filename);
    $driving_license = 'public/admin/assets/images/users/' . $filename;
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

// Check if referral code was used
if ($request->filled('referral_code')) {
    $referrer = User::where('referral_code', $request->referral_code)->first();

    if ($referrer) {
        // Get points
        $loyaltyPoint = LoyaltyPoints::where('id', 14)->first();
        if ($loyaltyPoint) {
            $pointsEarned = $loyaltyPoint->on_referal;

            // Update or create user loyalty
            $userLoyalty = UserLoyaltyEarning::firstOrNew(['user_id' => $referrer->id]);
            $userLoyalty->total_points += $pointsEarned;
            $userLoyalty->save();
        }
    }
}
// $document->save();
Mail::to($customer->email)->send(new UserCredentials($customer->name, $customer->email, $customer->phone));
return response()->json([
    // 'status' => true,
    'message' => 'Your account has been created successfully',
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



    public function login(Request $request){

        $validateUser = Validator::make(
            $request->all(),
            [
                // 'email' => 'required|email',
                'identifier' => 'required',
                'password' => 'required',
                // 'send_otp' => 'required',
                // 'fcm_token' => 'nullable|string',
            ],
            [
                'identifier.required' => 'The email is required',
                'password.required' => 'The password is required',
                // 'send_otp.required' => 'The otp field is required.',
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
                'message' => 'Invalid email',
            ], 404);
        }
    
        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                // 'status' => false,
                'message' => 'Invalid password',
            ], 401);
        }
    //     $otp = rand(100000, 999999);
    // $otpToken = Str::uuid(); // Unique token for OTP verification
    // $expiresAt = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes

    // Store OTP in the database
    // OTP::create([
    //     'identifier' => $identifier,
    //     'otp' => $otp,
    //     'otp_token' => $otpToken,
    //     // 'expires_at' => $expiresAt,
    // ]);

    // Send OTP via email (or SMS)
    // if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    //     Mail::to($identifier)->send(new OTPMail($otp));
    // }

    // return response()->json([
    //     'message' => 'OTP sent successfully.',
    //     'otp_token' => $otpToken, // Send OTP token to frontend
    // ], 200);
    
    $customer->update([
        'login_date' => Carbon::now(),
        'availability' => 1,
    ]);

        if ($request->fcm_token) {
            $customer->update(['fcm_token' => $request->fcm_token]);
        }

        return response()->json([
            // 'status' => true,
            'message' => 'Logged in successfully',
            // 'message' => 'OTP sent successfully.',
            'token' => $customer->createToken("API Token")->plainTextToken,
            // 'otp_token' => $otpToken, // Send OTP token to frontend 
            'fcm_token' => $customer->fcm_token,
            'data' => [
                'user_id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'emirate_id' => $customer->emirate_id,
                'passport' => $customer->passport,
                'driving_license' => $customer->driving_license,
            ],
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
        return response()->json(['message' => 'Invalid OTP token'], 400);
    }

    // Check if OTP is valid
    if ($otpRecord->otp !== $request->otp) {
        return response()->json(['message' => 'Invalid OTP'], 401);
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
        return response()->json(['message' => 'User not found'], 404);
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

    

    

    public function logout(Request $request){
        // $customer = $request->customer();
        // $customer->tokens()->delete();

        // return response()->json([
        //     'status' => true,
        //     'customer' => $customer,
        //     'message' => 'Logged Out successfully',
        // ],200);

        $customer = $request->user();

        $customer->update([
            'logout_date' => now()->toDateString(),
            'availability' => 0, // Stores only the date
        ]);

        $customer->tokens()->delete();      // Revoke all tokens associated with the customer

    return response()->json([
        // 'status' => true,
        'message' => 'Logged out successfully',
    ], 200);
    }

public function getCustomers(){
        $customers = User::all();

        return response()->json([
            'message' => 'Customers fetched successfully',
            'data' => $customers
        ], 200);
}


public function forgotPassword(Request $request)
{
    // Validate the email input
    // $request->validate([
    //     'email' => 'required|email|exists:users,email',
    // ]);

    // $email = $request->email;
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'This email does not exist'
        ], 404);
    }

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
        Mail::to($request->email)->send(new ForgotOTPMail($otp));
    // }

    return response()->json([
        'message' => 'OTP sent successfully',
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
        return response()->json(['message' => 'Invalid OTP token'], 400);
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
        return response()->json(['message' => 'User not found'], 404);
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

    $otpRecord = ForgotOTP::where('otp_token', $request->otp_token)->first();

    if (!$otpRecord) {
        return response()->json(['message' => 'Invalid OTP token'], 400);
    }
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

    if (Hash::check($request->new_password, $user->password)) {
        return response()->json([
            'message' => 'This password is already in use. Please choose a different password',
        ], 422);
    }
    // Update password
    $user->update(['password' => Hash::make($request->new_password)]);

    // Delete OTP record after successful password reset
    $otpRecord->delete();

    return response()->json([
        'message' => 'Password reset successfully',
    ], 200);
}

public function getProfile(Request $request)
    {
        $customer = Auth::user();

        return response()->json([
            // 'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => [
                'user_id' => $customer->id,
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
            'message' => 'Customer profile updated successfully',
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

public function getAllUser($id)
    {
         try {
            $user = User::where('id', '!=', $id)->latest()->get();
           return response()->json([
            'status' => 'success',
            'message' => 'data get successfully.',
            'user' => $user,
        ], 200);
 
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Failed to delete notification.', 'error' => $e->getMessage()], 500);
        }
    }
 

    public function customerdeactivateAccount(Request $request)
    {
        $customer = Auth::user();
    
        // Store notification for admin
        $notification = new DriverNotification();
        $notification->user_id = $customer->id;
        $notification->type = 'deactivation';
        $notification->message = "Customer {$customer->name} has requested account deactivation.";
        $notification->save();
    
        return response()->json([
            // 'status' => 'success',
            'message' => 'Account deactivation request sent to admin'
        ]);
    }

public function customerdeleteAccount(Request $request)
{
    $customerId = Auth::id(); // Get authenticated driver

    // Store the deactivation request
    DeleteRequest::create([
        'user_id' => $customerId,
        'deactivation_date' => Carbon::now()->toDateString(), // Store current date
    ]);

    return response()->json([
        'message' => 'Your account will be deleted within 14 days',
    ], 200);
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
Mail::to($driver->email)->send(new DriverCredentials($driver->name, $driver->email, $driver->phone, $request->password,'driver'));     

return response()->json([
// 'status' => true,
'message' => 'Your account has been created successfully',
'data' => ['driver'=>$driver,
        //    'document'=>$document
          ],
],200);
// }
}

public function driverlogin(Request $request){

    $validateUser = Validator::make(
        $request->all(),
        [
            // 'email' => 'required|email',
            'identifier' => 'required',
            'password' => 'required',
            // 'send_otp' => 'required',
        ],
        [
            'identifier.required' => 'The email is required',
            'password.required' => 'The password is required',
            // 'send_otp.required' => 'The otp field is required.',
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
            'message' => 'Invalid email',
        ], 404);
    }

    if (!Hash::check($request->password, $driver->password)) {
        return response()->json([
            // 'status' => false,
            'message' => 'Invalid password',
        ], 401);
    }
    // $otp = rand(100000, 999999);
    // $otpToken = Str::uuid(); // Unique token for OTP verification
    // $expiresAt = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes

    // Store OTP in the database
    // OTP::create([
    //     'identifier' => $identifier,
    //     'otp' => $otp,
    //     'otp_token' => $otpToken,
    //     // 'expires_at' => $expiresAt,
    // ]);

    // Send OTP via email (or SMS)
    // if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    //     Mail::to($identifier)->send(new OTPMail($otp));
    // }

    $driver->update([
        'login_date' => Carbon::now(),
        'availability' => 1,
    ]);
    
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
        'message' => 'Logged In successfully',
        // 'message' => 'OTP sent successfully.',
        // 'otp_token' => $otpToken, // Send OTP token to frontend 
        'fcm_token' => $driver->fcm_token,
        'token' => $driver->createToken("API Token")->plainTextToken,
        'data'=> ([
            'name' => $driver->name,
            'email' => $driver->email,
            'phone' => $driver->phone,
            'image' => $driver->image, // Assuming there's a profile image field
        ])
        // 'token_type' => 'Bearer',
    ], 200);
    
    
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
            $file = $request->file('license');
            $filename = time() . '_license_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $licensePath = 'public/admin/assets/images/users/' . $filename;

            $driver->license = $licensePath;
        
        $document->license = $licensePath;
        $document->save();

        LicenseApproval::where('driver_id', $driver->id)
        ->update(['image' => $licensePath]);
        }

        return response()->json(['message' => 'Document uploaded successfully', 'data' => $document], 200);
    }

    public function getLicenseStatus()
    {
        $driverId = Auth::id(); // Get the authenticated driver ID

        // Fetch driver license document with its approval status
        $licenses = DriverDocument::where('driver_id', $driverId)
            ->select('driver_id', 'license') // Selecting relevant columns
            ->with(['licenseApproval' => function ($query) use ($driverId) {
                $query->where('driver_id', $driverId)->select('driver_id', 'status');
            }])
            ->get();
           
            $formattedLicenses = $licenses->map(function ($document) {
                return [
                    'driver_id' => $document->driver_id,
                    'license' => $document->license,
                    'status' => optional($document->licenseApproval)->status == 1 ? 'Approved' : 'Rejected',
                ];
            });

        return response()->json([
            'licenses' => $formattedLicenses
        ], 200);
    }

public function driverforgotPassword(Request $request)
{
    // Validate the email input
    // $request->validate([
    //     'email' => 'required|email|exists:drivers,email',
    // ]);

    // $email = $request->email;
    $driver = Driver::where('email', $request->email)->first();

    if (!$driver) {
        return response()->json([
            'message' => 'This email does not exist'
        ], 404);
    }
    // Generate a unique token
    $otp = rand(100000, 999999);
    $otpToken = Str::uuid(); // Unique token for OTP verification
    // $expiresAt = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes

    // Store OTP in the database
    DriverForgotOTP::create([
        'identifier' => $request->email,
        'otp' => $otp,
        'otp_token' => $otpToken,
        // 'expires_at' => $expiresAt,
    ]);

    // Send OTP via email (or SMS)
    // if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        Mail::to($request->email)->send(new ForgotOTPMail($otp));
    // }

    return response()->json([
        'message' => 'OTP sent successfully',
        'otp_token' => $otpToken, // Send OTP token to frontend
    ], 200);
}

public function driverforgotverifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
        // 'otp_token' => 'required'
    ]);

    // Find OTP record in database
    $otpRecord = DriverForgotOTP::where('otp_token', $request->otp_token)->first();

    if (!$otpRecord) {
        return response()->json(['message' => 'Invalid OTP token'], 400);
    }

    // Check if OTP is valid
    if ($otpRecord->otp !== $request->otp) {
        return response()->json(['message' => 'Invalid OTP'], 401);
    }

    // Check if OTP has expired
    // if (Carbon::now()->gt($otpRecord->expires_at)) {
    //     return response()->json(['message' => 'OTP has expired.'], 401);
    // }

    // Retrieve driver (optional: if logging in)
    $user = Driver::where('email', $otpRecord->identifier)
                        // ->orWhere('phone', $otpRecord->identifier)
                        ->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    
    $otpRecord->update(['verified' => true]);

    return response()->json([
        'message' => 'OTP verified successfully',
        'otp_token' => $otpRecord->otp_token,
        // 'token' => $user->createToken("API Token")->plainTextToken,
    ], 200);
}

public function driverresetPassword(Request $request)
{
    // Validate the input
    $request->validate([
        'otp_token' => 'required|uuid',
        'new_password' => 'required|string|min:6|confirmed',
    ]);

    // Fetch OTP record using otp_token
    $otpRecord = DriverForgotOTP::where('otp_token', $request->otp_token)->first();

    if (!$otpRecord || !$otpRecord->verified) {
        return response()->json(['message' => 'Invalid or unverified OTP token'], 400);
    }

    // Find the user
    $user = Driver::where('email', $otpRecord->identifier)->first();

    if (!$user) {
        return response()->json(['message' => 'Driver not found'], 404);
    }

    if (Hash::check($request->new_password, $user->password)) {
        return response()->json([
            'message' => 'This password is already in use. Please choose a different password',
        ], 422);
    }
    // Update password
    $user->update(['password' => Hash::make($request->new_password)]);

    // Delete OTP record after successful password reset
    $otpRecord->delete();

    return response()->json([
        'message' => 'Password reset successfully',
    ], 200);
}

public function getDrivers(){
    $drivers = Driver::all();

    return response()->json([
        'message' => 'Drivers fetched successfully',
        'data' => $drivers
    ], 200);
}

public function driverlogout(Request $request){
    // $customer = $request->customer();
    // $customer->tokens()->delete();

    // return response()->json([
    //     'status' => true,
    //     'customer' => $customer,
    //     'message' => 'Logged Out successfully',
    // ],200);

    $driver = $request->user();
    $driver->update([
        'logout_date' => now()->toDateString(),
        'availability' => 0, // Stores only the date
    ]);
    $driver->tokens()->delete();      // Revoke all tokens associated with the customer

return response()->json([
    // 'status' => true,
    'message' => 'Logged Out successfully',
], 200);
}

    
    public function getDriverProfile(Request $request)
    {
        $driver = Auth::user();
        $driverLicense = DriverDocument::where('driver_id', $driver->id)
        ->value('license');
        return response()->json([
            // 'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => [
                'name' => $driver->name,
                'email' => $driver->email,
                'phone' => $driver->phone,
                'image' => $driver->image, 
                'license' => $driverLicense
            ],
        ], 200);
    }

    
    public function updateDriverProfile(Request $request)
    {


        // Get the authenticated user
        $driver = Auth::user();
        
       

        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->phone = $request->phone;
       
        // Handle profile image upload
        $driver->name = $request->name ?? $driver->name;
        $driver->email = $request->email ?? $driver->email;
        $driver->phone = $request->phone ?? $driver->phone; // Keep existing phone if not provided

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        
            $driver->update(['image' => $image]);
        }
    
        $driver->save();
    
        return response()->json([
            // 'status' => true,
            'message' => 'Driver profile updated successfully',
            'data' => [
                'name' => $driver->name,
                'email' => $driver->email,
                'phone' => $driver->phone,
                'image' => $driver->image ? asset($driver->image) : null,
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
        if ($request->hasFile('emirate_id')) {
            $file = $request->file('emirate_id');
            $filename = time() . '_emirate_id_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $emirate_id = 'public/admin/assets/images/users/' . $filename;

            $customer->emirate_id = $emirate_id;
        }
        
        if ($request->hasFile('passport')) {
            $file = $request->file('passport');
            $filename = time() . '_passport_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $passport = 'public/admin/assets/images/users/' . $filename;

            $customer->passport = $passport;
        }
        
        if ($request->hasFile('driving_license')) {
            $file = $request->file('driving_license');
            $filename = time() . '_driving_license_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $driving_license = 'public/admin/assets/images/users/' . $filename;

            $customer->driving_license = $driving_license;
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
            'message' => 'Customer document updated successfully',
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
    
    
    public function getAllDriver($id)
    {
         try {
            $driver = Driver::where('id', '!=', $id)->latest()->get();
           return response()->json([
            'status' => 'success',
            'message' => 'data get successfully.',
            'driver' => $driver,
        ], 200);
 
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Failed to delete notification.', 'error' => $e->getMessage()], 500);
        }
    }
 
    use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

public function socialLogin(Request $request)
{
   

    $socialColumn = $request->login_type === 'apple' ? 'apple_social_id' : 'google_social_id';

    // Try to find user by social_id
    $user = User::where($socialColumn, $request->social_id)->first();

    if ($user) {
        $user->fcm_token = $request->fcm_token ?? $user->fcm_token;
        $user->save();

        // $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'User login successful',
            'user' => $user,
            // 'token' => $token,
        ]);
    }

    // Handle file uploads
    $emirate_id = null;
    $passport = null;
    $driving_license = null;

    if ($request->hasFile('emirate_id')) {
        $file = $request->file('emirate_id');
        $filename = time() . '_emirate_id_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/users'), $filename);
        $emirate_id = 'public/admin/assets/images/users/' . $filename;
    }

    if ($request->hasFile('passport')) {
        $file = $request->file('passport');
        $filename = time() . '_passport_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/users'), $filename);
        $passport = 'public/admin/assets/images/users/' . $filename;
    }

    if ($request->hasFile('driving_license')) {
        $file = $request->file('driving_license');
        $filename = time() . '_driving_license_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/users'), $filename);
        $driving_license = 'public/admin/assets/images/users/' . $filename;
    }

    // Create new user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password), // fallback password
        'fcm_token' => $request->fcm_token,
        $socialColumn => $request->social_id,
        // 'status' => 1,
        'emirate_id' => $emirate_id,
        'passport' => $passport,
        'driving_license' => $driving_license,
    ]);

    // $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'message' => 'User registered via social login',
        'user' => $user,
        // 'token' => $token,
    ]);
}

    
}
