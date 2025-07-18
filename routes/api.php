<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\LoyaltyPointController;
use App\Http\Controllers\API\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//#################  Driver ########################
Route::get('/drivers', [AuthController::class, 'getDrivers']);
Route::post('/driverdocument',[AuthController::class,'driverdocument'])->middleware('auth:sanctum');
Route::post('/driversregister',[AuthController::class,'driverregister']);
Route::post('/driverlogin',[AuthController::class,'driverlogin'])->name('driverlogin');
Route::post('/driverlogout',[AuthController::class,'driverlogout'])->middleware('auth:sanctum');
Route::get('/getalldriver/{id}', [AuthController::class, 'getAllDriver']);

//##################### driver account #####################
Route::post('/accountdelete',[DriverController::class,'deleteAccount'])->middleware('auth:sanctum');
Route::post('/account-deactivate', [DriverController::class, 'deactivateAccount'])->middleware('auth:sanctum');

Route::post('/driverforgot-password', [AuthController::class, 'driverforgotPassword']);
Route::post('/driverforgot', [AuthController::class, 'driverforgotverifyOtp']);
Route::post('/driverreset', [AuthController::class, 'driverresetPassword']);

//################ driver availability #####################
Route::middleware('auth:sanctum')->post('/driveravailability', [DriverController::class, 'updateAvailability']);
Route::middleware('auth:sanctum')->get('/getdriveravailability', [DriverController::class, 'getAvailability']);
//################ licenseStatus ########################
Route::get('/license-status', [AuthController::class, 'getLicenseStatus'])->middleware('auth:sanctum');
Route::delete('/driverdocumentdelete/{id}', [AuthController::class, 'driverdocumentdestroy'])->middleware('auth:sanctum');
//################ Driver profile #######################
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getdriverprofile', [AuthController::class, 'getDriverProfile']);
    Route::post('/driverprofile', [AuthController::class, 'updateDriverProfile']);
});

//################## location Management ##################
Route::middleware('auth:sanctum')->post('/driverlocation', [DriverController::class, 'storeDriverLocation']);
Route::middleware('auth:sanctum')->get('/getdriverlocation', [DriverController::class, 'getDriverLocation']);

//################# Customer ###########################
Route::get('/customers', [AuthController::class, 'getCustomers']);
Route::post('/userdocument',[AuthController::class,'uploadDocument'])->middleware('auth:sanctum');
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/getalluser/{id}', [AuthController::class, 'getAllUser']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/forgot-verify-otp', [AuthController::class, 'forgotverifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

//################# customer account #####################
Route::post('/customeraccountdelete',[DriverController::class,'customerdeleteAccount'])->middleware('auth:sanctum');
Route::post('/customeraccountdeactivate',[DriverController::class, 'customerdeactivateAccount'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getprofile', [AuthController::class, 'getProfile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/documentprofile', [AuthController::class, 'updateDocument']);
    Route::post('/sociallogin', [AuthController::class, 'socialLogin']);
});
Route::post('/applelogin',[AuthController::class,'appleLogin']);
Route::post('/driverapplelogin',[AuthController::class,'driverappleLogin']);
//#################  Bookings  ###########################
Route::post('/bookings',[BookingController::class,'createBooking'])->middleware('auth:sanctum');
Route::get('/getbookings',[BookingController::class,'getUserBookings'])->middleware('auth:sanctum');
Route::get('/historybookings',[BookingController::class,'UserHistoryBookings'])->middleware('auth:sanctum');

//#################  DriverBookings  ###########################
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/driverbookings', [BookingController::class, 'getDriverBookings']);
    Route::post('/bookingupdate', [BookingController::class, 'updateBookingStatus']);
    Route::get('/bookinghistory', [BookingController::class, 'DriverBookingHistory']);
    Route::get('/driverrequests', [BookingController::class, 'getDriverBookingRequests']);
});
Route::get('/userdetails/{user_id}', [BookingController::class, 'getUserDetails']);

//#################  Payment  ############################
Route::post('/deposit', [PaymentController::class, 'processDeposit'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/wallet-history', [PaymentController::class, 'getWalletHistory']);

//#################  ContactUs  ##########################
Route::post('/contactus',[ContactUsController::class,'contact']);
Route::get('/drivercontactus', [ContactUsController::class, 'contactUs']);

//#################  Loyalty Points  #####################
Route::middleware('auth:sanctum')->get('/loyalty-points', [LoyaltyPointController::class, 'getLoyaltyPoints']);
Route::post('/share-referral', [LoyaltyPointController::class, 'earnLoyaltyPoints'])->middleware('auth:sanctum');
Route::get('/redeem-points', [LoyaltyPointController::class, 'getUserLoyaltyPoints'])->middleware('auth:sanctum');
Route::get('/loyalty', [LoyaltyPointController::class, 'getPoints']);
Route::middleware('auth:sanctum')->get('/loyaltylast-transaction', [LoyaltyPointController::class, 'lastLoyaltyTransaction']);


//#################  Redeemed Loyalty Points  ##############################
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/redeem-loyalty-points', [LoyaltyPointController::class, 'redeemLoyaltyPoints']);
    Route::get('/redemption-history', [LoyaltyPointController::class, 'getRedemptionHistory']);
});

//#################  Cars  ##############################
Route::get('/cars', [CarController::class, 'index'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->post('/price-details', [CarController::class, 'getCarPriceDetails']);
Route::middleware('auth:sanctum')->post('/filter-cars', [CarController::class, 'filterCars']);
Route::get('/relatedsearch', [CarController::class, 'relatedSearch'])->middleware('auth:sanctum');
Route::get('/car-details/{car_id}', [CarController::class, 'show'])->middleware('auth:sanctum');

//#################  Notification ##############################
Route::middleware('auth:sanctum')->get('/notifications', [NotificationController::class, 'getUserNotifications']);
Route::middleware('auth:sanctum')->get('/drivernotifications', [NotificationController::class, 'getDriverNotifications']);
Route::get('/notification/{id}', [NotificationController::class, 'showNotification'])->middleware('auth:sanctum');
Route::post('/clearnotification', [NotificationController::class, 'clearAll'])->middleware('auth:sanctum');
Route::post('/driverclearnotification', [NotificationController::class, 'Driverclearall'])->middleware('auth:sanctum');
Route::post('/sendnotification', [NotificationController::class, 'sendNotification']);
Route::post('/senddrivernotification', [NotificationController::class, 'driversendNotification']);

//#################  Favorite ##############################
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/favorite', [FavoriteController::class, 'toggleFavorite']);
    Route::get('/favorites', [FavoriteController::class, 'getFavorites']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
