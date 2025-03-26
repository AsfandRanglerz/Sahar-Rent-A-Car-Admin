<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\AuthController;
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
Route::post('/driverdocument',[AuthController::class,'driverdocument'])->middleware('auth:sanctum');
Route::post('/driversregister',[AuthController::class,'driverregister']);
Route::post('/driverlogin',[AuthController::class,'driverlogin'])->name('driverlogin');

Route::post('/driverforgot-password', [AuthController::class, 'driverforgotPassword']);
Route::post('/driverforgot', [AuthController::class, 'driverforgotverifyOtp']);
Route::post('/driverreset', [AuthController::class, 'driverresetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getdriverprofile', [AuthController::class, 'getDriverProfile']);
    Route::post('/driverprofile', [AuthController::class, 'updateDriverProfile']);
});
//################# Customer ###########################
Route::post('/userdocument',[AuthController::class,'uploadDocument'])->middleware('auth:sanctum');
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/forgot-verify-otp', [AuthController::class, 'forgotverifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/getprofile', [AuthController::class, 'getProfile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/documentprofile', [AuthController::class, 'updateDocument']);
});

//#################  Bookings  ###########################
Route::post('/bookings',[BookingController::class,'createBooking'])->middleware('auth:sanctum');
Route::get('/getbookings',[BookingController::class,'getUserBookings'])->middleware('auth:sanctum');
Route::get('/historybookings',[BookingController::class,'UserHistoryBookings'])->middleware('auth:sanctum');

//#################  DriverBookings  ###########################
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/driverbookings', [BookingController::class, 'getDriverBookings']);
    Route::post('/bookingupdate', [BookingController::class, 'updateBookingStatus']);
    Route::get('/bookinghistory', [BookingController::class, 'DriverBookingHistory']);
});
//#################  Payment  ############################
Route::post('/deposit', [PaymentController::class, 'processDeposit'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/wallet-history', [PaymentController::class, 'getWalletHistory']);

//#################  ContactUs  ##########################
Route::post('/contactus',[ContactUsController::class,'contact']);

//#################  Loyalty Points  #####################
Route::middleware('auth:sanctum')->get('/loyalty-points', [LoyaltyPointController::class, 'getLoyaltyPoints']);
Route::post('/share-referral', [LoyaltyPointController::class, 'earnLoyaltyPoints'])->middleware('auth:sanctum');
Route::get('/redeem-points', [LoyaltyPointController::class, 'getUserLoyaltyPoints'])->middleware('auth:sanctum');

//#################  Redeemed Loyalty Points  ##############################
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/redeem-loyalty-points', [LoyaltyPointController::class, 'redeemLoyaltyPoints']);
    Route::get('/redemption-history', [LoyaltyPointController::class, 'getRedemptionHistory']);
});

//#################  Cars  ##############################
Route::get('/cars', [CarController::class, 'index']);
Route::middleware('auth:sanctum')->post('/price-details', [CarController::class, 'getCarPriceDetails']);
Route::middleware('auth:sanctum')->post('/filter-cars', [CarController::class, 'filterCars']);

//#################  Notification ##############################
Route::middleware('auth:sanctum')->get('/notifications', [NotificationController::class, 'getUserNotifications']);

//#################  Favorite ##############################
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/favorite', [FavoriteController::class, 'toggleFavorite']);
    Route::get('/favorites', [FavoriteController::class, 'getFavorites']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
