<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ContactUsController;

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
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/documentprofile', [AuthController::class, 'updateDocument']);
});

//#################  Bookings  ###########################
Route::post('/bookings',[BookingController::class,'createBooking']);

//#################  Payment  ############################
Route::post('/deposit', [PaymentController::class, 'processDeposit'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/wallet-history', [PaymentController::class, 'getWalletHistory']);

//#################  ContactUs  ##########################
Route::post('/contactus',[ContactUsController::class,'contact']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
