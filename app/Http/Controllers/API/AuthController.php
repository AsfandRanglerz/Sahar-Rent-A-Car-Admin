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
use App\Models\Notification;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use App\Jobs\NotificationJob;
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
],405);
}
$emirate_id = null;
$emirate_id_back = null;
$passport = null;
$driving_license = null;
$driving_license_back = null;
$plainPassword = $request->password;
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
    $emirate_id_back = 'public/admin/assets/images/users/' . $filename;
}

if ($request->hasFile('emirate_id_back')) {
    $file = $request->file('emirate_id_back');
    $filename = time() . '_emirate_id_back_' . $file->getClientOriginalName();
    $file->move(public_path('admin/assets/images/users'), $filename);
    $emirate_id_back = 'public/admin/assets/images/users/' . $filename;
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

if ($request->hasFile('driving_license_back')) {
    $file = $request->file('driving_license_back');
    $filename = time() . '_driving_license_back' . $file->getClientOriginalName();
    $file->move(public_path('admin/assets/images/users'), $filename);
    $driving_license_back = 'public/admin/assets/images/users/' . $filename;
}

$customer = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'phone' => $request->phone,
    'password' => Hash::make($plainPassword),
    'emirate_id' => $emirate_id,
    'emirate_id_back' => $emirate_id_back, 
    'passport' => $passport,
    'driving_license' => $driving_license,
    'driving_license_back' => $driving_license_back,
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
    $referralCode = $request->referral_code;
    $referrer = User::where('referral_code', $request->referral_code)->first();

    if ($referrer) {
        // Get points
        $loyaltyPoint = LoyaltyPoints::where('id', 14)->first();
        if ($loyaltyPoint) {
            $pointsEarned = $loyaltyPoint->on_referal;
          
            $latestLoyalty = UserLoyaltyEarning::where('user_id', $referrer->id)
                ->orderByDesc('id')
                ->first();

            if ($latestLoyalty) {
                $latestLoyalty->total_points += $pointsEarned;
                $latestLoyalty->save();
            } else {
                // Create a new entry if none exists
                UserLoyaltyEarning::create([
                    'user_id' => $referrer->id,
                    'total_points' => $pointsEarned
                ]);
            }

             $referrer->referral_code = null; // or regenerate: strtoupper(Str::random(8))
            $referrer->save();
            // 🔔 Send push notification via job
            if ($referrer->fcm_token) {
                dispatch(new NotificationJob(
                    $referrer->fcm_token,
                    'Referral Bonus',
                    "You earned {$pointsEarned} loyalty points from a referral!",
                    [
                        'type' => 'referral_bonus',
                        'points' => $pointsEarned,
                        'referrer_id' => $referrer->id
                    ]
                ));
            }
             Notification::create([
                'customer_id'  => $referrer->id,
                'title'        => 'Referral Bonus Earned!',
                'description'  => 'You earned ' . $pointsEarned . ' loyalty points from your referral.',
                // 'seenByUser' => false, // uncomment if your table uses this
            ]);
        }
    }
}
// $document->save();
// Mail::to($customer->email)->send(new UserCredentials($customer->name, $customer->email, $customer->phone, $plainPassword));

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
            ], 405);
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
            ], 402);
        }
   
    if ($customer->status == 0) {
        return response()->json([
            'message' => 'Your account has been deactivated',
        ], 403);
    }

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
                'emirate_id_back' => $customer->emirate_id_back,
                'passport' => $customer->passport,
                'driving_license' => $customer->driving_license,
                'driving_license_back' => $customer->driving_license_back,
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
        return response()->json(['message' => 'Invalid OTP'], 402);
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
        'emarate_id_back' => $customer->emirate_id_back,
        'passport' => $customer->passport,
        'driving_license' => $customer->driving_license,
        'driving_license_back' => $customer->driving_license_back,
       
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
        // Mail::to($request->email)->send(new ForgotOTPMail($otp));
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
        return response()->json(['message' => 'Invalid OTP.'], 402);
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
            'message' => 'Profile retrieved successfully',
            'data' => [
                'user_id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'image' => $customer->image, 
                'emirate_id' => $customer->emirate_id,
                'emirate_id_back' => $customer->emirate_id_back,
                'passport' => $customer->passport,
                'driving_license' => $customer->driving_license,
                'driving_license_back' => $customer->driving_license_back,
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
            'message' => 'Profile updated successfully',
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

$plainPassword = $request->password;

if($validateUser->fails()){
return response()->json([
// 'status' => false,
'message' => $validateUser->errors()->first(),
// 'errors' => $validateUser->errors()->all(),
],403);
}

$driver = Driver::create([
'name' => $request->name,
'email' => $request->email,
'phone' => $request->phone,
'password' => Hash::make($plainPassword),
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
// Mail::to($driver->email)->send(new DriverCredentials($driver->name, $driver->email, $driver->phone, $plainPassword));     

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
        ], 403);
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
        ], 402);
    }
    
     if ($driver->status == 0) {
        return response()->json([
            'message' => 'Your account has been deactivated',
        ], 403);
    }
    
    $driver->update([
        'login_date' => Carbon::now(),
        'availability' => 1,
    ]);
    
        if ($request->fcm_token) {
            $driver->update(['fcm_token' => $request->fcm_token]);
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
            'driver_id' => $driver->id,
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

    // Find or create the driver's document record
    $document = DriverDocument::firstOrCreate(['driver_id' => $driver->id]);

    $licensePath = null;
    $licenseBackPath = null;

    // Handle license front upload
    if ($request->hasFile('license')) {
        $file = $request->file('license');
        $filename = time() . '_license_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/users'), $filename);
        $licensePath = 'public/admin/assets/images/users/' . $filename;

        // Save to driver and driver_documents
        $driver->license = $licensePath;
        $document->license = $licensePath;
    }

    // Handle license back upload
    if ($request->hasFile('license_back')) {
        $file = $request->file('license_back');
        $filename = time() . '_license_back_' . $file->getClientOriginalName();
        $file->move(public_path('admin/assets/images/users'), $filename);
        $licenseBackPath = 'public/admin/assets/images/users/' . $filename;

        $document->license_back = $licenseBackPath;
    }

    // Save updated document
    $document->save();

    // Only update license_approvals if at least license or license_back is uploaded
    if ($licensePath || $licenseBackPath) {
        LicenseApproval::updateOrCreate(
            ['driver_id' => $driver->id],
            [
                'email' => $driver->email,
                'name' => $driver->name,
                'image' => $licensePath, // fallback to existing
                'license_back' => $licenseBackPath,
            ]
        );
    }

    return response()->json([
        'message' => 'Document uploaded successfully',
        'data' => $document
    ], 200);
    }

    public function getLicenseStatus()
{
    $driverId = Auth::id(); // Get the authenticated driver ID

    $license = DriverDocument::where('driver_id', $driverId)
        ->select('id', 'driver_id', 'license', 'license_back')
        ->with(['licenseApproval' => function ($query) use ($driverId) {
            $query->where('driver_id', $driverId)->select('driver_id', 'status');
        }])
        ->first();

        if (!$license) {
        return response()->json([
            'message' => 'License status retrieved successfully',
            'data' => (object) []  
        ], 200);
    }
   
    $status = optional($license->licenseApproval)->status;

    // Determine readable status
    if ($status == 1) {
        $statusText = 'Approved';
    } elseif ($status == 0) {
        $statusText = 'Rejected';
    } elseif ($status == 2) {
        $statusText = 'Pending';
    } else {
        $statusText = 'Unknown';
    }

    return response()->json([
        'message' => 'License status retrieved successfully',
       'data' => [
        'id'            => $license->id,
        'driver_id'     => $license->driver_id,
        'license'       => $license->license,
        'license_back' => $license->license_back,
        'license_name'  => basename($license->license),
        'license_back_name' => basename($license->license_back),
        'status'        => $statusText,
    ]
    ], 200);
}

public function driverdocumentdestroy($id)
{
    $driverId = Auth::id(); // Authenticated driver

    $document = DriverDocument::where('id', $id)
                ->where('driver_id', $driverId)
                ->first();

// Delete related license approval if exists
    LicenseApproval::where('driver_id', $driverId)->delete();

    
    // Delete the document
    $document->delete();
    
        return response()->json([
            'message' => 'Document deleted'
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
        // Mail::to($request->email)->send(new ForgotOTPMail($otp));
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
        return response()->json(['message' => 'Invalid OTP'], 402);
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
    'message' => 'Logged out successfully',
], 200);
}

    
    public function getDriverProfile(Request $request)
    {
        $driver = Auth::user();
        $driverLicense = DriverDocument::where('driver_id', $driver->id)
        ->value('license');
         $status = DB::table('license_approvals')
        ->where('driver_id', $driver->id)
        ->value('status');

    // Convert status to readable format
    if ($status === 1) {
        $statusText = 'Approved';
    } elseif ($status === 0) {
        $statusText = 'Rejected';
    } elseif ($status === 2) {
        $statusText = 'Pending';
    } else {
        $statusText = 'Unknown';
    }
        return response()->json([
            // 'status' => true,
            'message' => 'Profile retrieved successfully',
            'data' => [
                'driver_id' => $driver->id,
                'name' => $driver->name,
                'email' => $driver->email,
                'phone' => $driver->phone,
                'image' => $driver->image, 
                'license' => $driverLicense,
                'status' => $statusText,
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
            'message' => 'Profile updated successfully',
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
        $emirate_id_back = null;
        $passport = null;
        $driving_license = null;
        $driving_license_back = null;
        
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

        if ($request->hasFile('emirate_id_back')) {
            $file = $request->file('emirate_id_back');
            $filename = time() . '_emirate_id_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $emirate_id_back = 'public/admin/assets/images/users/' . $filename;

            $customer->emirate_id_back = $emirate_id_back;
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

        if ($request->hasFile('driving_license_back')) {
            $file = $request->file('driving_license_back');
            $filename = time() . '_driving_license_back_' . $file->getClientOriginalName();
            $file->move(public_path('admin/assets/images/users'), $filename);
            $driving_license_back = 'public/admin/assets/images/users/' . $filename;

            $customer->driving_license_back = $driving_license_back;
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
                'emirate_id_back' => $emirate_id_back,
                'passport' => $passport,
                'driving_license' => $driving_license,
                'driving_license_back' => $driving_license_back,

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
 


public function socialLogin(Request $request)
    {
        try {
            $data = $request->only(['social_id', 'login_type', 'fcm_token', 'email', 'name', 'image']);
 
            $socialColumn = $data['login_type'] === 'apple' ? 'apple_social_id' : 'google_social_id';
 
            // Case 1: User exists by email but no social ID yet
            $user = User::whereNull($socialColumn)
                ->where('email', $data['email'])
                ->first();
 
            if ($user) {
                 if ($user->status == 0) {
                return response()->json([
                    'message' => 'Your account has been deactivated',
                ], 403);
            }
                $user->$socialColumn = $data['social_id'];
                $user->login_type = $data['login_type'];
                $user->fcm_token = $data['fcm_token'];
                $user->name = $data['name'] ?? $user->name;
                $user->image = $data['image'] ?? $user->image;
                $user->save();
                $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'user' => [
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'image' => $user->image,
                        ],

                    'token' => $token,
                ], 200);
            }
 
            // Case 2: User exists by social ID
            $user = User::where($socialColumn, $data['social_id'])->first();
 
            if ($user) {
                 if ($user->status == 0) {
                return response()->json([
                    'message' => 'Your account has been deactivated',
                ], 403);
            }
                $user->fcm_token = $data['fcm_token'];
                $user->name = $data['name'] ?? $user->name;
                $user->image = $data['image'] ?? $user->image;
                $user->login_type = $request->login_type;
                $user->save();

                $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }
 
            // Case 3: New user registration
            $user = new User();
            $user->email = $data['email'];
            $user->fcm_token = $data['fcm_token'];
            $user->login_type = $data['login_type'];
            $user->name = $data['name'] ?? null;
            $user->image = $data['image'] ?? null;
            $user->$socialColumn = $data['social_id'];
            $user->save();
            $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Logged in successfully',
                'type' => 'new',
                'user' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'image' => $user->image,
                ],

                'token' => $token,
            ], 200);
 
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
 
   public function driversocialLogin(Request $request)
    {
        try {
            $data = $request->only(['social_id', 'login_type', 'fcm_token', 'email', 'name', 'image']);
 
            $socialColumn = $data['login_type'] === 'apple' ? 'apple_social_id' : 'google_social_id';
 
            // Case 1: User exists by email but no social ID yet
            $user = Driver::whereNull($socialColumn)
                ->where('email', $data['email'])
                ->first();
 
            if ($user) {
                if ($user->status == 0) {
                return response()->json([
                    'message' => 'Your account has been deactivated',
                ], 403);
            }
                $user->$socialColumn = $data['social_id'];
                $user->login_type = $data['login_type'];
                $user->fcm_token = $data['fcm_token'];
                $user->name = $data['name'] ?? $user->name;
                $user->image = $data['image'] ?? $user->image;
                $user->save();
                $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }
 
            // Case 2: User exists by social ID
            $user = Driver::where($socialColumn, $data['social_id'])->first();
 
            if ($user) {
                if ($user->status == 0) {
                return response()->json([
                    'message' => 'Your account has been deactivated',
                ], 403);
            }
                $user->fcm_token = $data['fcm_token'];
                $user->name = $data['name'] ?? $user->name;
                $user->image = $data['image'] ?? $user->image;
                $user->login_type = $request->login_type;
                $user->save();
                $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }
 
            // Case 3: New user registration
            $user = new Driver();
            $user->email = $data['email'];
            $user->fcm_token = $data['fcm_token'];
            $user->login_type = $data['login_type'];
            $user->name = $data['name'] ?? null;
            $user->image = $data['image'] ?? null;
            $user->$socialColumn = $data['social_id'];
            $user->save();
            $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Logged in successfully',
                'type' => 'new',
                'user' => $user,
                'token' => $token,
            ], 200);
 
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function appleLogin(Request $request)
    {
        try {
            $data = $request->only(['social_id', 'fcm_token']);
   
            if (empty($data['social_id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Apple ID (social_id) is required.',
                ], 400);
            }
   
            $socialColumn = 'apple_social_id';
   
            // Check if user already exists by Apple social ID
            $user = User::where($socialColumn, $data['social_id'])->first();
   
            if (!$user) {
                // Create new user since email is not provided
                $user = new User();
                $user->$socialColumn = $data['social_id'];
            }
   
            // Update or set values
            $user->login_type = $request->input('login_type', 'apple');
            $user->fcm_token = $data['fcm_token'] ?? $user->fcm_token;
            $user->save();
            
             $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;
   
            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
                'token' => $token,
            ], 200);
           
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Apple login error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function driverappleLogin(Request $request)
    {
        try {
            $data = $request->only(['social_id', 'fcm_token']);
   
            if (empty($data['social_id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Apple ID (social_id) is required.',
                ], 400);
            }
   
            $socialColumn = 'apple_social_id';
   
            // Check if user already exists by Apple social ID
            $user = Driver::where($socialColumn, $data['social_id'])->first();
   
            if (!$user) {
                // Create new user since email is not provided
                $user = new Driver();
                $user->$socialColumn = $data['social_id'];
            }
   
            // Update or set values
            $user->login_type = $request->input('login_type', 'apple');
            $user->fcm_token = $data['fcm_token'] ?? $user->fcm_token;
            $user->save();
            
             $user->update([

                'login_date' => Carbon::now(),

                'availability' => 1,

            ]);
            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;
   
            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
                'token' => $token,
            ], 200);
           
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Apple login error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
}
